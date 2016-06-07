Yii 2 Image Behavior
=====================

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


All right! Now in your image directory we have 6 files with names like "\<timestapm\>\<default/small/fliped/...\>.\<file extension\>"

To get path to this image call $model->getImage('default') - where default is postfix of needed image

Parameters
----------

### Behavior parameters

* **imageAttr** (required, string) Name of model attribute that contains FileUploaded object.
* **images** (required, array[]) List of output images. Fields in this array must be format:

    [string $image_postfix => [string $modificator => array $params, ...]]

    where:
    * **image_postfix** postfix of concrete image
    * **modificator** modificator that will be applied to the image. (see modificator list and their params in modificators section)
    * **params** params of modificator



* **saveDirectory** (required, string) The directory where the images are saved.

* **options** (optional, array) where:
    * **deleteOldWhenUpdate** (optional, boolean) Default True. True - delete old images when upload new file in existing field, false - don't delete.
    * **ifNullBehavior** (optional, string) Default ImageBehavior::DELETE_IF_NULL. ImageBehavior::DELETE_IF_NULL - when attribute=NULL old images will be deleted, ImageBehavior::DO_NOTHING_IF_NULL - when attribute=NULL old images dont be deleted and field don be rewrite.


### Modificators

* **default** default image, params is empty array [].
* **resize** resize image, params [int width,int height].
* **flip** flip image, params [int direction].
* **sharpen** sharpen image, params [int amount].
* **crop** crop image, params [int width, int height,int offset_x,int offset_y].
* **rotate** rotate image, params [int degrees].

You can use this modificators in any number and combinations.

Full example of usage
----------

soon

