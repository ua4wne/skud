<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="login-container">
            <div class="center">
                <h1>
                    <i class="ace-icon fa fa-eye green"></i>
                    <span class="white" id="id-text2">Домовенок</span>
                </h1>
                <h4 class="blue" id="id-company-text">Система управления умным домом</h4>
            </div>

            <div class="space-6"></div>

            <div class="position-relative">
                <div id="login-box" class="login-box visible widget-box no-border">
                    <div class="widget-body">
                        <div class="widget-main">
                            <h4 class="header blue lighter bigger">
                                <i class="ace-icon fa fa-lock green"></i>
                                Авторизация
                            </h4>

                            <div class="space-6"></div>

                            <?php $form = ActiveForm::begin([
                                'id' => 'login-form',
                                'options' => [
                                    'class' => 'form-horizontal',
                                ],

                            ]); ?>
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                            <?= $form->field($model, 'password')->passwordInput() ?>
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>

                            <div class="row">
                                <div class="col-md-7 form-group">
                                    <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'style'=>'width: 80%;', 'name' => 'login-button']) ?>
                                </div>
                                <div class="col-md-5 form-group">
                                    <?= Html::a('Сбросить пароль', ['/user/default/password-reset-request'], ['class' => 'btn btn-danger']) ?>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div><!-- /.widget-main -->

                        <div class="toolbar clearfix">

                        </div>
                    </div><!-- /.widget-body -->
                </div><!-- /.login-box -->

            </div><!-- /.position-relative -->

        </div>
    </div>
</div>
