<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/responsive.css',
        'plugins/ekko-lightbox/ekko-lightbox.min.css',
        'plugins/jquery-slider/css/jquery.sidr.light.css',
    ];
    public $js = [
        'plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'plugins/breakpoints/breakpoints.js',
        'plugins/jquery-unveil/jquery.unveil.min.js',
        'plugins/jquery-slider/jquery.sidr.min.js',
        'plugins/ekko-lightbox/ekko-lightbox.min.js',
        'js/core.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset'
    ];
}
