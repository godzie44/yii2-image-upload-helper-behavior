<?php
namespace godzie44\yii\behaviors\image\helpers;
use yii\web\UploadedFile;
use yii\image\ImageDriver;
use Yii;

class ImageFile implements ImageInterface{
    /**
     * @property mixed $image
     * @property string $postfix
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
    public function save(NameMakerInterface $nameMaker)
    {
        $fileName = $nameMaker->getFullName($this->postfix);
       // $this->image->save($fileName, $quality = 90);
        echo "file saved $fileName";
    }

    /**
     * @return mixed
     */
    public function getSource(){
        return $this->image;
    }

}