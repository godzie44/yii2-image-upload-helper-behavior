<?php
namespace godzie44\yii\behaviors\image\helpers;
use yii\helpers\ArrayHelper;

class ImageList{
     /**
     * @param $path string
     * @param $name string
     * @return mixed
     */
    public function save($path)
    {

        foreach ($this->list as $image){
            /**
             * @var $image  ImageInterface
             */
            $image->save($path);
        }
    }

    public function add(ImageInterface $image){
        $this->list[] = $image;
    }

    /**
     * @var ImageInterface[]
     */
    private $list=[];

}