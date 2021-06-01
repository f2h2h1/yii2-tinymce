yii2-tinymce
================
这是 tinymce 在 yii2 的小部件。可以使用 elfinder 上传图片和文件。

欢迎 issues 和 pull

安装
```
composer require --prefer-dist f2h2h1/yii2-tinymce
```

在视图里引用
```php
echo \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
]);
```

## elfinder
tinymce 可以使用 elfinder 上传图片和文件。作者在开发时使用的是这个组件 mihaildev/yii2-elfinder

这是 mihaildev/yii2-elfinder 配置的参考
```php
    'controllerMap' => [
        'elfinder' => [
            'class' => \mihaildev\elfinder\Controller::class,
            'roots' => [ // 这里可以填多个 VolumeDriver
                [
                    'baseUrl'=>'@web',
                    'basePath'=>'@webroot', // 上传的 basePath
                    'path' => 'public', // 在上传 basePath 下的路径，就是实际的上传路径
                    'name' => 'public', // 图片/文件根目录名称，可随意。
                    'options' => [ // 这里才是 elfinder 的配置，上面几项都是 mihaildev/yii2-elfinder 的配置
                    ]
                ],
            ],
        ],
    ],
```

需要在视图里引入 elfinder 的依赖
```php
\mihaildev\elfinder\Assets::register($this);
// 如果需要设置中文或其它语言，需要引入这项
\mihaildev\elfinder\Assets::addLangFile(\Yii::$app->language, $this);
```

elFinder integrator 来自这个库 [nao-pon/tinymceElfinder](https://github.com/nao-pon/tinymceElfinder)

## 例子

例子1
```php
echo \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
]);
```

例子2 有默认值的
```php
echo \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
    'defaultValue' => 'qweasd', // 这是默认值
]);
```

例子3 修改 tinymce 的配置
```php
echo \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
    'options' => [ // 在这里添加 tinymce 的配置
        'plugins' => 'code',
    ],
    'defaultValue' => 'qweasd', // 这是默认值
]);
```

例子4 加上 elfinder
```php
echo \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
    'options' => [ // 在这里添加 tinymce 的配置
        'plugins' => 'image, link, media, imagetools, code',
    ],
    'elfinder' => [ // 在这里添加 elfinder 的配置
        'url' => \yii\helpers\Url::to(['elfinder/connect']),
        'uploadTargetHash' => 'l1_XA',
        'nodeId' => 'elfinder',
        'customData' => [
            \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken, // 这是 yii2 的 csrf ，如果禁用了 csrf 可以不加这一项
        ],
    ],
    'defaultValue' => 'qweasd', // 这是默认值
]);
```

例子4 给 tinymce 和 elfinder 加上中文
```php
echo \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
    'options' => [ // 在这里添加 tinymce 的配置
        'plugins' => 'image, link, media, imagetools, code',
        'language_url' => '/js/TinyMCE_zh_CN.js', // 这个路径需要能被页面访问到
        'language' => 'zh_CN',
    ],
    'elfinder' => [ // 在这里添加 elfinder 的配置
        'url' => \yii\helpers\Url::to(['elfinder/connect']),
        'nodeId' => 'elfinder',
        'customData' => [
            \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken, // 这是 yii2 的 csrf ，如果禁用了 csrf 可以不加这一项
        ],
        'lang' => 'zh_CN',
    ],
    'defaultValue' => 'qweasd', // 这是默认值
]);
```

例子5 在 form 表单里使用
```php
<form action="example/form" method="post">
<?= \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid', // 这是 tinymce 标签的 id
    'tagAttribute' => [ // 这里是标签属性
        'name' => 'tinymcename', // 如果这里为空，则会使用 tagId 作为 name
    ],
    'options' => [ // 在这里添加 tinymce 的配置
        'plugins' => 'image, link, media, imagetools, code',
        'language_url' => '/js/TinyMCE_zh_CN.js', // 这个路径需要能被页面访问到
        'language' => 'zh_CN',
    ],
    'elfinder' => [ // 在这里添加 elfinder 的配置
        'url' => \yii\helpers\Url::to(['elfinder/connect']),
        'nodeId' => 'elfinder',
        'customData' => [
            \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken, // 这是 yii2 的 csrf ，如果禁用了 csrf 可以不加这一项
        ],
        'lang' => 'zh_CN',
    ],
    'defaultValue' => 'qweasd', // 这是默认值
]); ?>
</form>
// 在后台里这样接收数据 $_POST['tinymcename']
```

例子6 多个实例
```php
$tinymce1 = \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid1', // 这是 tinymce 标签的 id
    'options' => [ // 在这里添加 tinymce 的配置
        'plugins' => 'image, link, media, imagetools, code',
        'language_url' => '/js/TinyMCE_zh_CN.js', // 这个路径需要能被页面访问到
        'language' => 'zh_CN',
    ],
    'elfinder' => [ // 在这里添加 elfinder 的配置
        'url' => \yii\helpers\Url::to(['elfinder/connect']),
        'nodeId' => 'elfinder',
        'customData' => [
            \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken, // 这是 yii2 的 csrf ，如果禁用了 csrf 可以不加这一项
        ],
        'lang' => 'zh_CN',
    ],
    'defaultValue' => 'qweasd', // 这是默认值
]);

$tinymce2 = \F2h2h1\Yii2Tinymce\TinyMCE::widget([
    'tagId' => 'tinymceid2', // 这是 tinymce 标签的 id
    'options' => [ // 在这里添加 tinymce 的配置
        'plugins' => 'image, link, media, imagetools, code',
        'language_url' => '/js/TinyMCE_zh_CN.js', // 这个路径需要能被页面访问到
        'language' => 'zh_CN',
    ],
    'elfinder' => [ // 在这里添加 elfinder 的配置
        'url' => \yii\helpers\Url::to(['elfinder/connect']),
        'nodeId' => 'elfinder',
        'customData' => [
            \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken, // 这是 yii2 的 csrf ，如果禁用了 csrf 可以不加这一项
        ],
        'lang' => 'zh_CN',
    ],
    'defaultValue' => 'qweasd', // 这是默认值
]);
echo $tinymce1;
echo $tinymce2;
```

例子7 在 ActiveForm 中使用
```html
<?php $form = ActiveForm::begin(['options'=>['class'=>'form-horizontal']]); ?>
<?= $form->field($model, 'content')->widget(\F2h2h1\Yii2Tinymce\TinyMCE::class, [
        'options' => [
            'language_url' => '/js/TinyMCE_zh_CN.js',
            'language' => 'zh_CN',
        ],
        'elfinder' => [
            'url' => \yii\helpers\Url::to(['elfinder/connect']),
            'nodeId' => 'elfinder',
            'customData' => [
                \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken,
            ],
            'lang' => 'zh_CN',
        ],
]); ?>
<?php ActiveForm::end(); ?>
```
