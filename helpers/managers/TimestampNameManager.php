<?php
namespace godzie44\yii\behaviors\image\helpers\managers;

use godzie44\yii\behaviors\image\helpers\NameManagerInterface;
use yii\base\Object;

class TimestampNameManager extends Object implements NameManagerInterface
{
    private $nameBody;
    private $filePath;
    private $extension = '';

    public function __construct($basePath)
    {
        $this->nameBody  = (new \DateTime())->getTimestamp();
        $this->filePath  = $basePath;
    }

    /**
     * @inheritdoc
     */
    public function generateName($postfix = '')
    {
        return $this->filePath . $this->nameBody . $postfix . $this->extension;
    }

    /**
    * @inheritdoc
    */
    public function getFile($baseName, $postfix)
    {
        $pathParts = pathinfo($baseName);
        $fileName  = $pathParts['dirname'] . DIRECTORY_SEPARATOR . $pathParts['filename']
            . $postfix . '.' . $pathParts['extension'];
        return file_exists($fileName) ? $fileName : null;
    }

    /**
     * @param string $extension file extension that be added to end of generate file name
     */
    public function setExtension($extension)
    {
        $this->extension = '.' . $extension;
    }
}