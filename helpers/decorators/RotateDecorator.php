<?php

namespace godzie44\yii\behaviors\image\helpers\decorators;

use \godzie44\yii\behaviors\image\helpers\ImageInterface;
use yii\base\Object;
use godzie44\yii\behaviors\image\helpers\NameManagerInterface;

/**
 * Class RotateDecorator
 * @package godzie44\yii\behaviors\image\helpers\decorators
 */
class RotateDecorator extends Object implements ImageInterface
{
    /**
     * @var ImageInterface
     */
    private $imageFile;
    
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
    public function save(NameManagerInterface $nameMaker)
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