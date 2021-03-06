<?php
namespace godzie44\yii\behaviors\image\helpers;

use yii\image\ImageDriver;
use Yii;

/**
 * Class ImageFile
 * @package godzie44\yii\behaviors\image\helpers
 */
class ImageFile implements ImageInterface{
    /**
     * @var mixed $image
     * @var string $postfix
     */
    private $image;
    private $postfix;

    public function __construct($filePath, $postfix){
        $driver = new ImageDriver;
        $driver->driver = 'GD';
        $this->image = $driver->load($filePath);
        $this->postfix = $postfix;
    }

    /**
     * @inheritdoc
     */
    public function save(NameManagerInterface $nameMaker)
    {
        $fileName = $nameMaker->generateName($this->postfix);
        $this->image->save($fileName, $quality = 100);
    }

    /**
     * @inheritdoc
     */
    public function getSource(){
        return $this->image;
    }

}