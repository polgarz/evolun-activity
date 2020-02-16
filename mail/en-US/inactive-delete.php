<?php
use yii\helpers\Url;
?>
<h1>Dear <?= $user[Yii::$app->controller->module->nameField] ?>!</h1>
<p>

    In order not to have inactive volunteers on the platform we decided that the acconts
    will be deleted after beeing inactive for
    <?= Yii::$app->controller->module->inactiveDeleteDays ?>
    days.
</p>

<p>
    You haven't been active in the last <?= Yii::$app->controller->module->inactiveDeleteDays ?> days,
    therefore we <b>deleted your account</b>.
    If you would like to be our volunteer in the future, please apply again.
</p>

<p>
    Hope we will meet again soon! :)
</p>
