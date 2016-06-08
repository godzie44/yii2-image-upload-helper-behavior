<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameManagerInterface;

/**
 * Class SharpenDecorator
 * @package godzie44\yii\behaviors\image\helpers\decorators
 */
class SharpenDecorator extends Object implements ImageInterface
{
    /**
     * @var ImageInterface
     */
    private $imageFile;

    /**
     * @var int
     */
    private $amount;

    /**
     * @param ImageInterface $imageFile
     * @param integer[]      $config
     */
    public function __construct(ImageInterface $imageFile, array $config)
    {
        $this->imageFile = $imageFile;

        $this->amount = $config[0];
    }

    /**
     * @inheritdoc
     */
    public function save(NameManagerInterface $nameMaker)
    {
        $this->imageFile->getSource()->sharpen($this->amount);

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