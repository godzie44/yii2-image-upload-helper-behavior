<?php
/**
 * Created by PhpStorm.
 * User: dks
 * Date: 01.06.2016
 * Time: 12:46
 */

namespace godzie44\yii\behaviors\image\helpers;


interface ImageInterface {

    /**
     * @param $path string
     * @return mixed
     */
    public function save($path);

    /**
     * @return mixed
     */
    public function getSource();
}