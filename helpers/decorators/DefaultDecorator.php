<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameManagerInterface;

class DefaultDecorator extends Object implements ImageInterface
{
    /**
     * @property ImageInterface
     */
    private $imageFile;


    public function __construct(ImageInterface $imageFile, array $config = [])
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @inheritdoc
     */
    public function save(NameManagerInterface $nameMaker)
    {
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