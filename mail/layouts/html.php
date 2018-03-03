<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <style type="text/css">
        table {
            border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #039; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
        }
        caption{text-transform: uppercase;}
        th {text-transform: uppercase;
            border: 1px solid #0865c2;
            padding: 15px;}
        td {border: 1px dashed #0865c2;
            padding: 10px 20px;
            text-align: left;}
    </style>

</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
