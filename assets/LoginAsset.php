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
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/fonts.googleapis.com.css',
        'css/ace.min.css',
        'css/ace-skins.min.css',
        'css/ace-rtl.min.css',
    ];
    public $js = [

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
