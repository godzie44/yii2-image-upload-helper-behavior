<?php
namespace godzie44\yii\behaviors\image\helpers;

use yii\helpers\ArrayHelper;
use godzie44\yii\behaviors\image\helpers\NameMakerInterface;
class ImageList
{
    /**
     * @param $path string
     */
    public function save(NameMakerInterface $nameMaker)
    {

        /**
         * @var $image  ImageInterface
         */
        foreach ($this->list as $image) {
            $image->save($nameMaker);
        }
    }

    public function add(ImageInterface $image)
    {
        $this->list[] = $image;
    }

    private $list = [];

}