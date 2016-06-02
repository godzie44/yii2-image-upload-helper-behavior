<?php
/**
 * Created by PhpStorm.
 * User: dks
 * Date: 02.06.2016
 * Time: 17:32
 */

namespace godzie44\yii\behaviors\image\helpers;


interface NameMakerInterface
{
    /**
     * @param string $postfix
     * @return string
     */
    public function getFullName($postfix = '');

} 