<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameMakerInterface;

class ResizeDecorator extends Object implements ImageInterface
{
    /**
     * @property ImageInterface
     */
    private $imageFile;

    /**
     * @property int $width
     * @property int $height
     * @property string $postfix
     */
    private $width;
    private $height;


    public function __construct(ImageInterface $imageFile, array $config)
    {
        $this->imageFile = $imageFile;

        $this->width = $config[0];
        $this->height = $config[1];
    }

    /**
     * @inheritdoc
     */
    public function save(NameMakerInterface $nameMaker)
    {
        $this->imageFile->getSource()->resize($this->width, $this->height, \yii\image\drivers\Image::HEIGHT);

        $this->imageFile->save($nameMaker);
    }




    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->imageFile->getSource();
    }
}