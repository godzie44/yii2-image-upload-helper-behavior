<?php

namespace godzie44\yii\behaviors\image;

use yii\base\Exception;
use yii\base\ExitException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use godzie44\yii\behaviors\image\helpers;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
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
     * @property array $images  (see before)
     */
    public $images;


    /**
     * @var string $saveDirectory directory of saved file
     */
    public $saveDirectory;

    /**
     * @var string $imageAttr file attribute name in model
     */
    public $imageAttr;

    /**
     * @var bool $deleteOnUpdate delete old images when update image field
     */
    public $deleteOnUpdate = true;
    /**
     * @var helpers\ImageList
     */
    private $imageList;

    /**
     * @var \yii\web\UploadedFile
     */
    private $uploadImage;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
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
     *
     */
    private function deletePreviousImages()
    {
        foreach ($this->images as $imagePostfix => $imageOptions) {
            $path = $this->getOldImage($imagePostfix);
            if (file_exists($path)) {
                unlink($path);
            }
        }

    }


    /**
     * @param $event
     * @throws Exception
     */
    public function beforeUpdate($event)
    {
        if ($this->owner->validate() && $this->owner->{$this->imageAttr} instanceof \yii\web\UploadedFile) {
            if ($this->deleteOnUpdate) {
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
            return $path_parts['dirname'] . DIRECTORY_SEPARATOR . $path_parts['filename'] . $postfix . "." . $path_parts['extension'];
        } else {
            throw new Exception('cant find image');
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
     * @return string
     * @throws Exception
     */
    public function getImage($postfix)
    {
        return $this->getConcreteImage($postfix, $this->owner->{$this->imageAttr});
    }


}