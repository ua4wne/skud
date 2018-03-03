<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
<p><strong>ФИО:&nbsp;<?= $this->params['fio'] ?></strong></p>
<p><strong>ПЕРИОД:&nbsp;<?= date('m'); ?> месяц <?= date('Y'); ?> года</strong></p>
<p><strong>АДРЕС:&nbsp;<?= $this->params['address'] ?></strong></p>
<p><strong>ФЛС:&nbsp;<?= $this->params['fls'] ?></strong></p>

<table>
    <caption>ПОКАЗАНИЯ ПРИБОРОВ УЧЕТА</caption>
    <tr><th>Услуга</th><th>Номер</th><th>Предыдущее</th><th>Текущее</th></tr>
        <?= $this->params['table'] ?>
</table>
<br>
<p style="color:brown;">Сообщение отправлено почтовым роботом<br>
системы умного дома <strong>"Домовенок"</strong></p>
