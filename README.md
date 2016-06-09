Yii 2 Image Behavior
=====================
This behavior will help you to:
    - easy save uploaded image
    - easy save modified copy of the initial image
    - give access to uploaded images
    - delete images when delete record, update images when update record

Installation
------------
```bash
composer require godzie44/yii2-image-behavior
```

Test
-------------
```
$ codecept run
```

Usage
-------------
In controller.

Just put in image attribute FileUpload object (like in <a href="http://www.yiiframework.com/doc-2.0/guide-input-file-upload.html">official guide</a>) and call $model->save() method

In model (example)
```php
 public function behaviors()
    {
        return [
             [
                'class' => \godzie44\yii\behaviors\image\ImageBehavior::className(),

                'imageAttr' => 'avatar', //attribute in model, instace of FileUploaded

                'images' => [ // array of images that we whant to save
                   'default' => ['default' => []],
                   'small' => ['resize' => [150,200]],
                   'fliped' => ['flip' => [30]],
                   'sharped' => ['sharpen' => [30]],
                   'croped' => ['crop' => [30,40,20,10]],
                   'medium-rotate' => ['resize' => [300,400], 'rotate' => [40]],
                ],

                'saveDirectory' => Yii::getAlias('@webroot/uploads/avatars/'),
            ],
            ...
        ];
    }
```


Now in 'saveDirectory' directory we have 6 images with names like "\<timestapm\>\<default/small/fliped/...\>.\<file extension\>"

Get path to this images by calling $model->getImage('default') - where default is postfix of needed image

Parameters
----------

### Behavior parameters

* **imageAttr** (required, string) Name of model attribute that contains FileUploaded object.
* **images** (required, array[]) List of output images. Fields in this array must be format:

    [string image_postfix => [string modificator => array $params, ...]]

    where:
    * **image_postfix** string, postfix of concrete image
    * **modificator**   string, modificator that will be applied to the image. (see modificator list and their params in modificators section)
    * **params**        array, params of modificator



* **saveDirectory** (required, string) The directory where the images are saved.

* **options** (optional, array) where:
    * **deleteOldWhenUpdate** (optional, boolean) Default True. True - delete old images when upload new file in existing field, false - don't delete.
    * **ifNullBehavior**      (optional, string) Default ImageBehavior::DELETE_IF_NULL. ImageBehavior::DELETE_IF_NULL - when attribute=NULL old images will be deleted, ImageBehavior::DO_NOTHING_IF_NULL - when attribute=NULL old images dont be deleted and field don be rewrite.


### Modificators

* **default**   default image, params is empty array [].
* **resize**    resize image, params [int width,int height].
* **flip**      flip image, params [int direction].
* **sharpen**   sharpen image, params [int amount].
* **crop**      crop image, params [int width, int height,int offset_x,int offset_y].
* **rotate**    rotate image, params [int degrees].

You can use this modificators in any number and combinations.

Simple example of usage (user profile with avatar)
----------

In controller 

```php
public function actionProfile() {
        $model =  User::findOne(Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post())) {
            $model->avatar = UploadedFile::getInstance($model, 'avatar');
            $model->save();
        }
        return $this->render('profile', ['model' => $model,]);
    }
```

User model like:

```php
class User extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            [['name', 'id'], 'safe'],
            [['avatar'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => ImageBehavior::className(),
                'saveDirectory' => Yii::getAlias('@webroot/uploads/avatars/'),
                'imageAttr' => 'avatar',
                'images' => [
                  '_default' => ['default' => []], //save default upload image
                  '_small' => ['resize' => [150,200]], //and save resized copy
                ],
                'options' => [
                    'ifNullBehavior' => ImageBehavior::DO_NOTHING_IF_NULL, 
                    //when avatar attribute contains null, don't need to deleted images and rewrite avatar field
                ]
            ],
        ];
    }


   //getter of resized image
    public function getSmallAvatar(){
        return = $this->getImage('_small');
    }

}
```

In view 

```php
$form = ActiveForm::begin();

echo $form->field($model, 'name')->textInput();

echo Html::img($model->smallAvatar);
echo $form->field($model, 'avatar')->fileInput()->label('change avatar');

echo Html::submitButton('Save', ['class' => 'btn btn-primary']);
ActiveForm::end();
  
```

