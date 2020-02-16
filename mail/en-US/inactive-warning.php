<?php
use yii\helpers\Url;
?>
<h1>Dear <?= $user[Yii::$app->controller->module->nameField] ?>!</h1>
<p>
    It seems that you haven't logged in the platform for more than <?= Yii::$app->controller->module->inactiveWarningDays ?> days.
</p>
<p>
    In order not to have inactive volunteers in the platform we decided that the acconts will be deleted after being inactive for
    <?= Yii::$app->controller->module->inactiveDeleteDays ?> days.
    If you want to keep your acces please log in now. If you don't, you won't be able to log in after the date stated above.
</p>

<p>
    You can reach the platform on the following link:<br />
    <a href="<?=Url::base(true) ?>"><?=Url::base(true) ?></a><br />
    If you forgot your password you can ask for a new one <a href="<?=Url::base(true) ?>/user/default/reset-password-request">here</a>.
</p>
