<?php
use yii\helpers\Url;
?>
<h1>Kedves <?= $user[Yii::$app->controller->module->nameField] ?>!</h1>
<p>
    Úgy látjuk, hogy legalább <?= Yii::$app->controller->module->inactiveWarningDays ?> napja nem léptél be az önkéntes felületre.
</p>
<p>
    Annak érdekében, hogy ne legyenek inaktív önkéntesek a felületen, <?= Yii::$app->controller->module->inactiveDeleteDays ?>
    napos inaktivitás után törlődnek a fiókok.
    Ha nem szeretnéd, hogy töröljük a hozzáférésed, lépj be most! Amennyiben ezt nem teszed meg, a fent jelzett időponttól már nem fogsz tudni bejelentkezni.
</p>

<p>
    A felületet az alábbi linken éred el:<br />
    <a href="<?=Url::base(true) ?>"><?=Url::base(true) ?></a><br />
    Amennyiben nem emlékszel a belépési jelszavadra, kérj új jelszót <a href="<?=Url::base(true) ?>/user/default/reset-password-request">ide</a> kattintva.
</p>
