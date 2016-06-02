<?php
/**
 * Created by PhpStorm.
 * User: dks
 * Date: 01.06.2016
 * Time: 12:46
 */

namespace godzie44\yii\behaviors\image\helpers;

use godzie44\yii\behaviors\image\helpers\NameMakerInterface;

interface ImageInterface
{

    /**
     * @param  NameMakerInterface $nameMaker
     * @return mixed
     */
    public function save(NameMakerInterface $nameMaker);

    /**
     * @return mixed
     */
    public function getSource();
}