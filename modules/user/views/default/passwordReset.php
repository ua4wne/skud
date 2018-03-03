<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\PasswordResetForm */
$this->title = 'Восстановление пароля';
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
                                <?= Html::encode($this->title)  ?>
                            </h4>
                            <div class="space-6"></div>
                            <p>Введите новый пароль</p>
                            <?php $form = ActiveForm::begin(['id' => 'password-reset-form','options' => [
                                    'class' => 'form-horizontal',
                                ]]); ?>
                                    <div class="form-group">
                                        <?= $form->field($model, 'password')->passwordInput() ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
                                    </div>
                                <?php ActiveForm::end(); ?>
                            <div class="toolbar clearfix">

                            </div>
                        </div><!-- /.widget-body -->
                    </div><!-- /.login-box -->

                </div><!-- /.position-relative -->

            </div>
        </div>
    </div>