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

                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?= Yii::$app->user->identity->image ?? '/images/male.png' ?>" alt="image" />

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
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

                <a href="/main/visitor/index" class="btn btn-success" title="Посетители">
                    <i class="ace-icon fa fa-vcard-o"></i>
                </a>

                <a href="/main/event/index" class="btn btn-info" title="События СКУД">
                    <i class="ace-icon fa fa-bell-o"></i>
                </a>

                <a href="/admin/events/index" class="btn btn-warning" title="Системный журнал">
                    <i class="ace-icon fa fa-comments-o"></i>
                </a>

                <a href="/admin/device/index" class="btn btn-danger" title="Устройства">
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
                        <a href="/admin/user">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Пользователи
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/admin/card">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Карты доступа
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/admin/time-zone">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Временные зоны
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/admin/event-type">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Виды событий
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/admin/car-type">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Виды автотранспорта
                        </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/admin/doc-type">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Виды документов
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
                        <a href="/admin/device">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Контроллеры СКУД
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="">
                <a href="/main/renter">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="menu-text">Организации</span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="/main/visitor">
                    <i class="menu-icon fa fa-user"></i>
                    <span class="menu-text">Сотрудники</span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="">
                <a href="/main/task">
                    <i class="menu-icon fa fa-tasks"></i>
                    <span class="menu-text">Очередь заданий</span>
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
                <a href="/main/event/index">
                    <i class="menu-icon fa fa-bell-o" aria-hidden="true"></i>
                    <span class="menu-text"> События </span>
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
