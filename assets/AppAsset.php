<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

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
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/jquery-ui.custom.min.css',
        'css/fullcalendar.min.css',
        'css/fonts.googleapis.com.css',
        'css/ace.min.css',
        'css/ace-skins.min.css',
        'css/ace-rtl.min.css',
        'css/dataTables.bootstrap.css',
        'css/dataTables.responsive.css',
        'css/style.css',
        'css/morris.css',
        'css/custom.css',
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/jquery.ui.touch-punch.min.js',
        'js/moment.min.js',
        'js/fullcalendar.min.js',
        'js/bootbox.js',
        'js/ace-elements.min.js',
        'js/ace-extra.min.js',
        'js/ace.min.js',
        'js/jquery.dataTables.min.js',
        'js/dataTables.bootstrap.min.js',
        'js/dataTables.responsive.js',
        //'js/raphael-min.js',
        'js/raphael-2.1.4.min.js',
        'js/morris.js',
        'js/caldraw.js',
        //'js/justgage.js'
    ];
    public $jsOptions = [
        //'position' => \yii\web\View::POS_HEAD, //влияет на работу фильтрации в таблицах представлений - виджет GridView!!!
    ];
    public $depends = [
        'app\assets\IESupportAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
