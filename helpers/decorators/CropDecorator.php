<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameMakerInterface;

/**
 * Class SharpenDecorator
 * @package godzie44\yii\behaviors\image\helpers\decorators
 */
class CropDecorator extends Object implements ImageInterface
{
    /**
     * @var ImageInterface
     */
    private $imageFile;

    /**
     * @var int $width
     * @var int $height
     * @var int $offset_x
     * @var int $offset_y
     */
    private $width;
    private $height;
    private $offset_x;
    private $offset_y;

    /**
     * @param ImageInterface $imageFile
     * @param integer[]      $config
     */
    public function __construct(ImageInterface $imageFile, array $config)
    {
        $this->imageFile = $imageFile;

        $this->width = $config[0];
        $this->height = $config[1];
        $this->offset_x = $config[2];
        $this->offset_y = $config[3];

    }

    /**
     * @inheritdoc
     */
    public function save(NameMakerInterface $nameMaker)
    {
        $this->imageFile->getSource()->crop($this->width, $this->height, $this->offset_x, $this->offset_y);

        $this->imageFile->save($nameMaker);
    }

    /**
     * @inheritdoc
     */
    public function getSource()
    {
        return $this->imageFile->getSource();
    }
}