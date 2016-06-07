<?php

use godzie44\yii\behaviors\image\ImageBehavior;

class BehaviorTest extends \yii\codeception\TestCase
{
    public $appConfig  = '@tests/unit/_config.php';
    public $testImage  = '@tests/_data/yii2framework.jpg';
    public $testImage2 = '@tests/_data/yii1framework.jpg';

    protected function createUser()
    {
        $uploadFile = $this->getUploadFile($this->testImage);
        $user       = new \data\User;
        $user->setAttributes(['id' => '1', 'name' => 'tester', 'avatar' => $uploadFile]);
        return $user;
    }


    private function getUploadFile($src)
    {
        $uploadFile           = Yii::createObject(\yii\web\UploadedFile::className());
        $uploadFile->name     = 'no_matter.jpg';
        $uploadFile->tempName = Yii::getAlias($this->testImage);
        $uploadFile->type     = \yii\helpers\FileHelper::getMimeType(Yii::getAlias($this->testImage));
        return $uploadFile;
    }


    public function testCrateThenDeleteEntity()
    {

        $user = $this->createUser();


        $this->assertTrue($user->save(), 'save() method ');

        $smallImage = $user->getImage('small');
        $this->assertTrue(file_exists($smallImage), 'created small image ');

        $defaultImage = $user->getImage('default');
        $this->assertTrue(file_exists($defaultImage), 'created default image ');

        $rotatedImage = $user->getImage('rotated');
        $this->assertTrue(file_exists($rotatedImage), 'created rotate image  ');

        $flipedImage = $user->getImage('fliped');
        $this->assertTrue(file_exists($flipedImage), 'created fliped image  ');

        $sharpedImage = $user->getImage('sharped');
        $this->assertTrue(file_exists($sharpedImage), 'created sharped image ');

        $cropedImage = $user->getImage('croped');
        $this->assertTrue(file_exists($cropedImage), 'created croped image ');

        $combinedImage = $user->getImage('combined');
        $this->assertTrue(file_exists($combinedImage), 'created combined image ');

        $this->assertEquals(1, $user->delete());
        $this->assertCount(0, \yii\helpers\FileHelper::findFiles(Yii::getAlias('@tests/_data/avatars/')), 'images deleted');

    }

    public function testUpdateEntityWithImage()
    {
        $user = $this->createUser();
        $this->assertTrue($user->save(), 'save() method ');
        $oldFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@tests/_data/avatars/'));

        $uploadFile   = $this->getUploadFile($this->testImage2);
        $user->avatar = $uploadFile;
        $user->save();
        $newFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@tests/_data/avatars/'));

        $this->assertNotContains($oldFiles, $newFiles, 'old images deleted');
        $this->assertCount(7, $newFiles, 'new images created');

        $this->assertEquals(1, $user->delete());
    }

    public function testUpdateEntityWithNull()
    {
        /** default settings delete images when NULL in attr */
        $user = $this->createUser();
        $this->assertTrue($user->save(), 'save() method ');

        $uploadFile   = NULL;
        $user->avatar = $uploadFile;
        $user->save();
        $newFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@tests/_data/avatars/'));
        $this->assertCount(0, $newFiles, 'images deleted');
        $this->assertEquals(1, $user->delete());


        /** set behavior DO_NOTHING_IF_NULL */

        $user = $this->createUser();

        /**  change default settings */
        $behavior                            = $user->getBehavior('imageBehavior');
        $behavior->options['ifNullBehavior'] = ImageBehavior::DO_NOTHING_IF_NULL;

        $this->assertTrue($user->save(), 'save() method ');
        $oldFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@tests/_data/avatars/'));

        $uploadFile   = NULL;
        $user->avatar = $uploadFile;
        $user->save();
        $newFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@tests/_data/avatars/'));


        $this->assertEquals($oldFiles, $newFiles, 'images not changed');
        $this->assertEquals(1, $user->delete());

    }


}