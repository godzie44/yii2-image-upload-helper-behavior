<?php
namespace godzie44\yii\behaviors\image\helpers;

use yii\base\Object;

class TimestampNameMaker implements NameMakerInterface {
    private $nameBody;
    private $filePath;
    public function __construct($path){
        $this->nameBody = (new \DateTime())->getTimestamp();
        $this->filePath = $path;
    }

    /**
     * @inheritdoc
     */
    public function getFullName($postfix = ''){
        return $this->filePath . $this->nameBody . $postfix;
    }
}