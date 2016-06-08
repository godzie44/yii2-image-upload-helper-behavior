<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameManagerInterface;

/**
 * Class ResizeDecorator
 * @package godzie44\yii\behaviors\image\helpers\decorators
 */
class ResizeDecorator extends Object implements ImageInterface
{
    /**
     * @var ImageInterface
     */
    private $imageFile;

    /**
     * @var int $width
     * @var int $height
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
    public function save(NameManagerInterface $nameMaker)
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