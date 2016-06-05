<?php
namespace godzie44\yii\behaviors\image\helpers;

use godzie44\yii\behaviors\image\helpers;
use \godzie44\yii\behaviors\image\helpers\decorators;
use yii\base\Object;

class ImageFactory extends Object
{
    /**
     * @param $fileName
     * @param $postfix
     * @return ImageInterface
     */
    private function getOriginalImage($fileName, $postfix)
    {
        return new ImageFile($fileName, $postfix);
    }

    /**
     * @param string $prefix decorator class prefix
     * @param ImageInterface $object
     * @param array $params
     * @return ImageInterface
     * @throws \yii\base\InvalidConfigException
     */
    private function addDecorator($prefix, $object, $params)
    {
        $className = $this->decorators[ucfirst($prefix)];
        return \Yii::createObject($className, [$object, $params]);
    }

    /**
     * @param string $fileName full path to file
     * @param string $postfix
     * @param array $imageOptions
     * @return ImageInterface
     */
    public function getImage($fileName, $postfix, array $imageOptions)
    {
        $image = $this->getOriginalImage($fileName, $postfix);
        foreach ($imageOptions as $option => $value) {
            $image = $this->addDecorator($option, $image, $value);
        }
        return $image;
    }

    /**
     * @inheritdoc
     */
    function init()
    {
        $this->decorators = [
            'Sharpen' => decorators\SharpenDecorator::className(),
            'Resize' => decorators\ResizeDecorator::className(),
            'Rotate' => decorators\RotateDecorator::className(),
            'Crop' => decorators\CropDecorator::className(),
            'Flip' => decorators\FlipDecorator::className(),
            'Default' => decorators\DefaultDecorator::className()
        ];
    }

    private $decorators;


} 