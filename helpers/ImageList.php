<?php
namespace godzie44\yii\behaviors\image\helpers;

use yii\helpers\ArrayHelper;

class ImageList
{
    /**
     * @param NameManagerInterface $nameMaker
     */
    public function save(NameManagerInterface $nameMaker)
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