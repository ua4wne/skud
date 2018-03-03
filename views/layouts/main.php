<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\InfoBadge;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="no-skin">
<?php $this->beginBody() ?>
<div id="navbar" class="navbar navbar-default          ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="/" class="navbar-brand">
                <small>
                    <i class="fa fa-eye"></i>
                    <?= Yii::$app->name ?>
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="grey dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-tasks"></i>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-clock-o"></i>

                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar">

                            </ul>
                        </li>

                        <li class="dropdown-footer">
                        </li>
                    </ul>
                </li>

                <li class="purple dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                        <span class="badge badge-important">2</span>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon"></i>
                            Событий - 2
                        </li>
                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar navbar-pink">

                            </ul>
                        </li>

                        <li class="dropdown-footer">
                            <a href="/admin/events/index">
                                Подробнее
                                <i class="ace-icon fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="green dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
                        <span class="badge badge-success">2</span>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-envelope-o"></i>
                            Новых сообщений - 2
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar">

                            </ul>
                        </li>

                        <li class="dropdown-footer">
                            <a href="/main/syslog/index">
                                Все сообщения
                                <i class="ace-icon fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?= Yii::$app->user->identity->image ?>" alt="image" />
                        <span class="user-info">
									<?= Yii::$app->user->identity->fname .'<br>'.Yii::$app->user->identity->lname ?>
								</span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="#">
                                <i class="ace-icon fa fa-cog"></i>
                                Settings
                            </a>
                        </li>

                        <li><?= Html::a("<i class=\"ace-icon fa fa-user\"></i> Профиль", '/user/profile/index', [
                                    'data' => [
                                        'method' => 'post'
                                    ],
                                ]
                            );?>
                        </li>

                        <li class="divider"></li>

                        <li><?= Html::a("<i class=\"ace-icon fa fa-sign-out\"></i> Выход", '/user/default/logout', [
                                    'data' => [
                                        'method' => 'post'
                                    ],
                                ]
                            );?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>

<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar responsive ace-save-state">
        <script type="text/javascript">
            try{ace.settings.loadState('sidebar')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                <button class="btn btn-success">
                    <i class="ace-icon fa fa-signal"></i>
                </button>

                <a href="/main/stock/store" class="btn btn-info" title="Остатки на складе">
                    <i class="ace-icon fa fa-stack-overflow"></i>
                </a>

                <a href="/main/syslog/index" class="btn btn-warning" title="Системный журнал">
                    <i class="ace-icon fa fa-envelope-o"></i>
                </a>

                <a href="/main/device/index" class="btn btn-danger" title="Устройства">
                    <i class="ace-icon fa fa-cogs"></i>
                </a>
            </div>

            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>

                <span class="btn btn-info"></span>

                <span class="btn btn-warning"></span>

                <span class="btn btn-danger"></span>
            </div>
        </div><!-- /.sidebar-shortcuts -->

        <ul class="nav nav-list">
            <li class="active">
                <a href="/">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> Dashboard </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-gears"></i>
                    <span class="menu-text">Настройки</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Система
                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="submenu">
                            <li class="">
                                <a href="/main/config">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Параметры
                                </a>

                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/main/config/sms">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Отправка смс
                                </a>

                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/main/config/mqtt">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    MQTT
                                </a>

                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>

                    <li class="">
                        <a href="/main/locations">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Локации
                        </a>

                        <b class="arrow"></b>
                    </li>


                    <li class="">
                        <a href="/main/device">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Оборудование
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/main/device-type">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Типы устройств
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-database"></i>
                    <span class="menu-text">Учет</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    <li class="">

                        <a href="/main/registration">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> Счетчики </span>
                        </a>

                        <b class="arrow"></b>

                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Мой склад
                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="submenu">
                            <li class="">
                                <a href="/main/stock/store">
                                    <i class="menu-icon fa fa-tachometer"></i>
                                    <span class="menu-text"> Остатки </span>
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/main/stock/store/inventory">
                                    <i class="menu-icon fa fa-search"></i>
                                    <span class="menu-text"> Инвентаризация </span>
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/main/stock/category">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Категории
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <li class="">
                                <a href="/main/stock/cell">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Места хранения
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <li class="">
                                <a href="/main/stock/material">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Номенклатура
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/main/stock/unit">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Ед. измерения
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="">
                <a href="/main/task">
                    <i class="menu-icon fa fa-calendar"></i>
                    <span class="menu-text">Задачи</span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="/main/report">
                    <i class="menu-icon fa fa-line-chart"></i>
                    <span class="menu-text">Отчеты</span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="/admin/events/index">
                    <i class="menu-icon fa fa-bell-o" aria-hidden="true"></i>
                    <span class="menu-text"> События 2</span>
                </a>

                <b class="arrow"></b>
            </li>
            <li class="">
                <a href="/main/syslog/index">
                    <i class="menu-icon fa fa-envelope-o" aria-hidden="true"></i>
                    <span class="menu-text"> Сообщения 2</span></span>
                </a>

                <b class="arrow"></b>
            </li>
        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if( Yii::$app->session->hasFlash('success') ): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo Yii::$app->session->getFlash('success'); ?>
                            </div>
                        <?php endif;?>
                        <?php if( Yii::$app->session->hasFlash('error') ): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo Yii::$app->session->getFlash('error'); ?>
                            </div>
                        <?php endif;?>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            <?= $content ?>
            </div>
        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
                <span class="bigger-120">
                    <span class="blue bolder">Smartcon &copy; 2018</span>
                </span>
            </div>
        </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- ace scripts -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
