<?php
/**
 * Created by PhpStorm.
 * User: dks
 * Date: 01.06.2016
 * Time: 12:46
 */

namespace godzie44\yii\behaviors\image\helpers;

use godzie44\yii\behaviors\image\helpers\NameManagerInterface;

/**
 * Interface ImageInterface
 * @package godzie44\yii\behaviors\image\helpers
 */
interface ImageInterface
{

    /**
     * @param  NameManagerInterface $nameMaker
     * @return mixed
     */
    public function save(NameManagerInterface $nameMaker);

    /**
     * @return mixed
     */
    public function getSource();
}