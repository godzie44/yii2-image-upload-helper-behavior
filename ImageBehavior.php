<?php

namespace godzie44\yii\behaviors\image;
use yii\base\Exception;
use yii\db\ActiveRecord;
use godzie44\yii\behaviors\image\helpers;
use yii\base\Behavior;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class ImageBehavior extends Behavior{


    /**
     * $options['resize'] array
     * $options['resize'][~resize_postfix~] = [~width~, ~height~]  param resize-postfix is postfix of resized image name
     * @property array $options (see before)
     */
    public $options;

    /**
     *
     * $additionalImages[$imagePostfix => $modificatorsArray] array
     * @var $imagePostfix string postfix in name of modification image
     * @var $modificatorsArray array array of modificators that will be applied to image
     * $modificatorsArray[$modificatorName => $modificatorValues] sample : ['resize' => [400;600]]
     * @property array $additionalImages (see before)
     */
    public $additionalImages;
    /**
     * @property $postfix postfix of saved image file
     */
    public $postfix;

    /**
     * @property $saveDirectory directory of saved file
     */
    public $saveDirectory;

    /**
     * @property $imageAttr file attribute name in model
     */
    public $imageAttr;

    /**
     * @property $imageList ImageList
     */
    private $imageList;

    /**
     * @property $uploadImage UploadedFile
     */
    private  $uploadImage;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
        ];
    }

    public function initBehavior(){
        if ($this->owner->{$this->imageAttr} instanceof \yii\web\UploadedFile) {
            $this->uploadImage = $this->owner->{$this->imageAttr};
        } else {
            throw new Exception('file object must be instance of UploadFile');
        }

        $fileName = $this->uploadImage->tempName;
        
        $imageFactory = new helpers\ImageFactory();
        $this->imageList = new helpers\ImageList();

        $this->imageList->add(
            $imageFactory->getImage($fileName, $this->postfix, [])
        );

        foreach ($this->additionalImages as $imagePostfix=>$imageOptions){
            $this->imageList->add(
                $imageFactory->getImage($fileName, $imagePostfix, $imageOptions)
            );
        }



    }


    /**
     * @param $event; save image here
     */
    public function afterUpdate($event)
    {

    }


    public function beforeInsert($event)
    {
//
//        if ($event->sender->{$this->imgAttr} instanceof \yii\web\UploadedFile) {
//            $event->sender->{$this->imgAttr}->name = $this->createFileName($event->sender->{$this->imgAttr});
//
//        }
    }

    public function beforeUpdate($event)
    {
        if ($this->owner->validate()) {
            $this->initBehavior();
            $this->imageList->save();
            exit;
        }

    }

}