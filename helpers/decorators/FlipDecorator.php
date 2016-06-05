<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameMakerInterface;

/**
 * Class SharpenDecorator
 * @package godzie44\yii\behaviors\image\helpers\decorators
 */
class FlipDecorator extends Object implements ImageInterface
{
    /**
     * @var ImageInterface
     */
    private $imageFile;

    /**
     * @var int
     */
    private $direction;

    /**
     * @param ImageInterface $imageFile
     * @param integer[]      $config
     */
    public function __construct(ImageInterface $imageFile, array $config)
    {
        $this->imageFile = $imageFile;

        $this->direction = $config[0];
    }

    /**
     * @inheritdoc
     */
    public function save(NameMakerInterface $nameMaker)
    {
        $this->imageFile->getSource()->flip($this->direction);

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