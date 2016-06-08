<?php

namespace godzie44\yii\behaviors\image;

use yii\base\Exception;
use yii\db\ActiveRecord;
use godzie44\yii\behaviors\image\helpers;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class ImageBehavior
 * @package godzie44\yii\behaviors\image
 */
class ImageBehavior extends Behavior
{

    /**
     * array [imagePostfix => [modificator => value, modificator2 => value2, ...]]
     *  string imagePostfix      string, postfix in name of image
     *      string modificator   name of modificator
     *      array  value         array of modificator values
     *
     * @var array $images (see before)
     */
    public $images;

    /**
     * @var array
     */
    public $options;

    /**
     * @var string $saveDirectory directory of saved file
     */
    public $saveDirectory;

    /**
     * @var string $imageAttr file attribute name in model
     */
    public $imageAttr;

    /**
     * @var helpers\ImageList
     */
    private $imageList;

    /**
     * @var helpers\NameManagerInterface
     */
    private $nameManager;

    private $defaultOptions = [
        'deleteOldWhenUpdate' => true,
        'ifNullBehavior'      => self::DELETE_IF_NULL,
    ];

    public function  init()
    {
        $this->setDefaultOptions();
        $this->nameManager = \Yii::createObject(helpers\managers\TimestampNameManager::className(),
                                                [$this->saveDirectory]);
    }

    private function setDefaultOptions()
    {
        foreach ($this->defaultOptions as $name => $value) {
            if (!isset($this->options[$name])) {
                $this->options[$name] = $value;
            }
        }
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'deleteImages',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'addImages',
            ActiveRecord::EVENT_BEFORE_INSERT => 'addImages',
        ];
    }

    /**
     * @throws Exception
     */
    public function addImages()
    {

        if ($this->owner->validate() && $this->owner->{$this->imageAttr} instanceof \yii\web\UploadedFile) {

            if ($this->options['deleteOldWhenUpdate']) {
                $this->deletePreviousImages();
            }

            /** @var UploadedFile */
            $uploadImage = $this->owner->{$this->imageAttr};

            /** init imageList and uploadImage */
            $this->imageList = $this->getImageList($uploadImage->tempName);

            /**add file extension to name manager*/
            $this->nameManager->setExtension($uploadImage->getExtension());

            /** save images */
            $this->imageList->save($this->nameManager);

            /** change image attr value to new image name without postfix */
            $this->owner->{$this->imageAttr} = $this->nameManager->generateName();
        }

        if ($this->owner->validate() && NULL === $this->owner->{$this->imageAttr}) {
            if ($this->options['ifNullBehavior'] === static::DELETE_IF_NULL) {
                $this->deletePreviousImages();
            } elseif ($this->options['ifNullBehavior'] === static::DO_NOTHING_IF_NULL) {
                $this->owner->{$this->imageAttr} = $this->owner->oldAttributes[$this->imageAttr];
            }
        }
    }

    /**
     * @param string $sourceFileName
     * @return helpers\ImageList
     */
    private function getImageList($sourceFileName)
    {
        $imageFactory = new helpers\ImageFactory();
        $imageList    = new helpers\ImageList();

        foreach ($this->images as $imagePostfix => $imageOptions) {
            $imageList->add(
                $imageFactory->getImage($sourceFileName, $imagePostfix, $imageOptions)
            );
        }

        return $imageList;
    }

    /**
     * delete old images
     */
    private function deletePreviousImages()
    {

        if (empty($this->owner->oldAttributes[$this->imageAttr])
            || (string)$this->owner->oldAttributes[$this->imageAttr] === ''
        ) {
            return;
        }

        foreach ($this->images as $imagePostfix => $imageOptions) {
            $path = $this->getOldImage($imagePostfix);
            if (file_exists($path)) {
                unlink($path);
            }
        }

    }

    /**
     * delete all images before record was deleted
     */
    public function deleteImages()
    {
        $this->deletePreviousImages();
    }

    /**
     * @param $postfix
     * @param $commonPath
     * @return string
     * @throws Exception
     */
    private function getConcreteImage($postfix, $commonPath)
    {
        if (ArrayHelper::keyExists($postfix, $this->images)) {
            return $this->nameManager->getFile($commonPath, $postfix);
        } else {
            throw new Exception('Image is not defined in images array');
        }
    }

    /**
     * @param $postfix
     * @return string
     * @throws Exception
     */
    private function getOldImage($postfix)
    {
        return $this->getConcreteImage($postfix, $this->owner->oldAttributes[$this->imageAttr]);
    }

    /**
     * @param $postfix
     * @return string|null
     * @throws Exception
     */
    public function getImage($postfix)
    {
        return $this->getConcreteImage($postfix, $this->owner->{$this->imageAttr});
    }

    const DELETE_IF_NULL     = 'delete';
    const DO_NOTHING_IF_NULL = 'do nothing';

}