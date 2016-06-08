<?php


namespace godzie44\yii\behaviors\image\helpers;


interface NameManagerInterface
{
    /**
     * @param string $basePath path that be include in full file name
     */
    public function __construct($basePath);

    /**
     * @param string $postfix
     * @return string
     */
    public function generateName($postfix = '');

    /**
     * @param string $extension file extension that be added to end of generate file name
     */
    public function setExtension($extension);


    /**
     * @param string $postfix
     * @param string $baseName
     * @return string|null
     */
    public function getFile($baseName, $postfix);

} 