<?php
namespace godzie44\yii\behaviors\image\helpers;

use yii\base\Object;

class TimestampNameMaker implements NameMakerInterface {
    private $nameBody;
    private $filePath;
    private $extension;

    public function __construct($path, $extension){
        $this->nameBody = (new \DateTime())->getTimestamp();
        $this->filePath = $path;
        $this->extension = $extension;
    }

    /**
     * @inheritdoc
     */
    public function getFullName($postfix = ''){
        return $this->filePath . $this->nameBody . $postfix . '.' . $this->extension;
    }
}