<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameMakerInterface;
class RotateDecorator extends Object implements ImageInterface
{
    /**
     * @property ImageInterface
     */
    private $imageFile;

    /**
     * @property int    $width
     * @property int    $height
     * @property string $postfix
     */
    private $degrees;

    /**
     * @param ImageInterface $imageFile
     * @param integer[]      $config
     */
    public function __construct(ImageInterface $imageFile, array $config)
    {
        $this->imageFile = $imageFile;

        $this->degrees = $config[0];
    }

    /**
     * @inheritdoc
     */
    public function save(NameMakerInterface $nameMaker)
    {
        $this->imageFile->getSource()->rotate($this->degrees);

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