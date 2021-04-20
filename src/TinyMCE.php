<?php
namespace F2h2h1\Yii2Tinymce;

class TinyMCE extends \yii\base\Widget
{
    const DIV = 'div';
    const TEXTAREA = 'textarea';

    public $tagId = '';

    /**
     * it should only be div or textarea
     */
    public $tagType = '';

    /**
     * @see \yii\helpers\BaseHtml::renderTagAttributes()
     */
    public $tagAttribute = [];

    /**
     * default value of TinyMCE
     */
    public $defaultValue = '';

    /**
     * selector of TinyMCE
     */
    public $selector = '';

    /**
     * options of TinyMCE
     *
     * example
     * ```
     * 'options' => [
     *     'plugins' => 'image, link, media, imagetools, code',
     *     'language_url' => '/js/TinyMCE_zh_CN.js',
     *     'language' => 'zh_CN',
     * ],
     * ```
     *
     * @see https://www.tiny.cloud/docs/configure/integration-and-setup/
     */
    public $options = [];

    /**
     * options of elfinder
     *
     * it should be array. if this param is empty, elfinder does not initialize
     *
     * example
     * ```
     * 'elfinder' => [
     *     'url' => \yii\helpers\Url::to(['elfinder/connect']),
     *     'uploadTargetHash' => 'l1_XA',
     *     'nodeId' => 'elfinder',
     *     'customData' => [
     *         \Yii::$app->request->csrfParam => \Yii::$app->request->csrfToken,
     *     ],
     *     'lang' => 'zh_CN'
     * ],
     * ```
     *
     * @see https://github.com/nao-pon/tinymceElfinder
     * @see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
     */
    public $elfinder = [];

    /**
     * return tinymce plugins list
     *
     * @param array|null $plugins
     * @return string
     * @see https://www.tiny.cloud/docs/plugins/opensource/
     */
    public static function tinyMCEPlugins($plugins = null)
    {
        if ($plugins === null) {
            $plugins = [
                'print',
                'preview',
                'searchreplace',
                'autolink',
                'directionality',
                'visualblocks',
                'visualchars',
                'fullscreen',
                'imagetools',
                'image',
                'link',
                'media',
                'codesample',
                'table',
                'charmap',
                'hr',
                'pagebreak',
                'nonbreaking',
                'anchor',
                'toc',
                'insertdatetime',
                'advlist',
                'lists',
                'wordcount',
                'textpattern',
                'noneditable',
                'help',
                'charmap',
                'emoticons',
                'code',
            ];
        }

        return join(' ', $plugins);
    }

    /**
     * return tinymce toolbar
     *
     * @param array|null $toolbar
     * @return string
     * @see https://www.tiny.cloud/docs/configure/editor-appearance/#toolbar
     */
    public static function tinyMCEToolbar($toolbar = null)
    {
        if ($toolbar === null) {
            // undo redo | image | styleselect | bold italic | alignleft aligncenter alignright alignjustify | table 
            $toolbar = [
                ['undo', 'redo'],
                ['image'],
                ['styleselect'],
                ['bold', 'italic'],
                ['alignleft', 'aligncenter', 'alignright', 'alignjustify'],
                ['table'],
                ['preview'],
            ];
        }

        return join(' | ', array_map(function ($item) {
                        return join(' ', $item);
                    }, $toolbar));
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (empty($this->tagAttribute['id'])) {
            $this->tagAttribute['id'] = $this->tagId;
        }
        if (empty($this->tagAttribute['name'])) {
            $this->tagAttribute['name'] = $this->tagAttribute['id'];
        }
        if (!empty($this->tagAttribute['id'])) {
            $this->options['selector'] = '#'.$this->tagAttribute['id'];
        }
        if (!empty($this->selector)) {
            $this->options['selector'] = $this->selector;
        }
        if (empty($this->tagType)) {
            $this->tagType = self::TEXTAREA;
        }

        if (!isset($this->options['plugins'])) {
            $this->options['plugins'] = self::tinyMCEPlugins();
        } else {
            $this->options['plugins'] = self::tinyMCEPlugins($this->options['plugins']);
        }
        if (!isset($this->options['toolbar'])) {
            $this->options['toolbar'] = self::tinyMCEToolbar();
        } else {
            $this->options['toolbar'] = self::tinyMCEToolbar($this->options['toolbar']);
        }
        if (!isset($this->options['relative_urls'])) {
            $this->options['relative_urls'] = false;
        }

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (!($this->tagType == self::DIV || $this->tagType == self::TEXTAREA)) {
            return '<span>error tag</span>';
        }

        $view = $this->getView();

        $error = $this->registerPlugin($view);
        if ($error !== null) {
            return '<span>'.$error.'</span>';
        }

        if ($this->tagType == self::TEXTAREA) {
            $tagName = '';
            if (!empty($this->tagAttribute['name'])) {
                $tagName = $this->tagAttribute['name'];
            }
            return \yii\helpers\Html::textArea($tagName, $this->defaultValue, $this->tagAttribute);
        }
        return \yii\helpers\Html::tag('div', $this->defaultValue, $this->tagAttribute);
    }

    protected function registerPlugin($view)
    {
        if (empty($this->options['selector'])) {
            return 'missing attribute selector';
        }
        Asset::register($view);

        $js = '';
        $init = '';
        foreach ($this->options as $key => $item) {
            $init .= sprintf("%s: '%s',", $key, $item);
        }
        if (count($this->elfinder) > 0) {
            if (empty($this->elfinder['url'])) {
                return 'missing elfinder connector url';
            }
            list(, $path) = \Yii::$app->assetManager->publish(__DIR__);
            $view->registerJsFile($path.'/tinymceElfinder.js');
            $init .= sprintf("%s: %s,", 'file_picker_callback', 'mceElf.browser');
            // $init .= sprintf("%s: %s,", 'images_upload_handler', 'mceElf.uploadHandler');
            $js .= 'const mceElf = new tinymceElfinder('.json_encode($this->elfinder).');';
        }

        $js .= 'tinymce.init({'.$init.'});';
        $view->registerJs($js);

        return null;
    }
}
