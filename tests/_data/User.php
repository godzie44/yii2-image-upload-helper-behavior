<?php

namespace data;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use godzie44\yii\behaviors\image\ImageBehavior;

class User extends ActiveRecord


{



    public function init(){

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * CommentatorInterface realization
     * @return false|mixed|string
     */
    public function getCommentatorAvatar()
    {

        if (null !== $this->smallAvatar){

            return $this->smallAvatar;
        }

        return '/uploads/icons/teams/no-image.png';//$this->avatar_url;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['avatar'], 'file', 'extensions' => 'png, jpg'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function behaviors()
    {
        return [
            'imageBehavior' => [
                'class' => ImageBehavior::className(),
                'saveDirectory' => Yii::getAlias('@tests/_data/avatars/'),
                'imageAttr' => 'avatar',
                'images' => [
                    'default' => ['default' => []],
                    'small' => ['resize' => [150,200]],
                    'rotated' => ['rotate' => [30]],
                    'fliped' => ['flip' => [30]],
                    'sharped' => ['sharpen' => [30]],
                    'croped' => ['crop' => [30,40,20,10]],
                    'combined' => ['resize' => [300,400], 'rotate' => [40], 'sharpen' => [30]],

                ],
                'options' => [
                    'deleteOldWhenUpdate' => TRUE,
                    //'ifNullBehavior' => ImageBehavior::DO_NOTHING_IF_NULL,
                ]



            ],
        ];
    }



    /**
     * @return string
     */
    public function getDefaultAvatar(){
        $wr = Yii::getAlias('@webroot');
        return str_replace($wr, '', $this->getImage('default'));
    }

    /**
     * @return string
     */
    public function getSmallAvatar(){
        $fileName = $this->getImage('small');
        if (null === $fileName){
            return null;
        }
        $wr = Yii::getAlias('@webroot');
        return str_replace($wr, '', $fileName);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'avatar' => 'Avatar',
        ];
    }

}
