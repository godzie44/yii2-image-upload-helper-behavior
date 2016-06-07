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
     *
     * $images[$imagePostfix => $modificatorsArray] array
     * @var            $imagePostfix      string postfix in name of modification image
     * @var            $modificatorsArray array array of modificators that will be applied to image
     * $modificatorsArray[$modificatorName => $modificatorValues] sample : ['resize' => [400;600]]
     * @property array $images            (see before)
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
     * @var \yii\web\UploadedFile
     */
    private $uploadImage;

    private $defaultOptions = [
        'deleteOldWhenUpdate' => true,
        'ifNullBehavior' => self::DELETE_IF_NULL,
    ];

    public function  init(){
        foreach ($this->defaultOptions as $name=>$value) {
            if (!isset($this->options[$name])) {
                $this->options[$name] = $value;
            }
        }
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'addImages',
            ActiveRecord::EVENT_BEFORE_INSERT => 'addImages',
        ];
    }

    /**
     * @return helpers\ImageList
     */
    private function initImageList()
    {
        $fileName     = $this->uploadImage->tempName;
        $imageFactory = new helpers\ImageFactory();
        $imageList    = new helpers\ImageList();

        foreach ($this->images as $imagePostfix => $imageOptions) {
            $imageList->add(
                $imageFactory->getImage($fileName, $imagePostfix, $imageOptions)
            );
        }

        return $imageList;
    }


    /**
     * @param $event
     * @throws Exception
     */
    public function addImages()
    {

        if ($this->owner->validate() && $this->owner->{$this->imageAttr} instanceof \yii\web\UploadedFile) {

            if ($this->options['deleteOldWhenUpdate']) {
                $this->deletePreviousImages();
            }

            $this->uploadImage = $this->owner->{$this->imageAttr};
            /** init imageList and uploadImage */
            $this->imageList = $this->initImageList();

            /** init NameMaker */
            $fileExt = $this->uploadImage->getExtension();

            $nameMaker = new helpers\name_makers\TimestampNameMaker($this->saveDirectory, $fileExt);

            /** save images */
            $this->imageList->save($nameMaker);

            /** change image attr value to new image name without postfix */
            $this->owner->{$this->imageAttr} = $nameMaker->getFullName();
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
     *
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
     * delete all images before records was deleted
     * @param $event
     */
    public function beforeDelete($event)
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
            $path_parts = pathinfo($commonPath);
            $fileName   = $path_parts['dirname'] . DIRECTORY_SEPARATOR . $path_parts['filename'] . $postfix . "." . $path_parts['extension'];
            return file_exists($fileName) ? $fileName : null;

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