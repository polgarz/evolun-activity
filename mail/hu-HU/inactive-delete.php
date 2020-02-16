<?php
use yii\helpers\Url;
?>
<h1>Kedves <?= $user[Yii::$app->controller->module->nameField] ?>!</h1>
<p>
    Annak érdekében, hogy ne legyenek inaktív önkéntesek a felületen,
    <?= Yii::$app->controller->module->inactiveDeleteDays ?> napos inaktivitás után törlődnek a fiókok.
</p>

<p>
    Az elmúlt <?= Yii::$app->controller->module->inactiveDeleteDays ?> napban nem mutattál aktívitást,
    ezért a <b>fiókodat töröltük.</b>
    Amennyiben szeretnél a későbbiekben nálunk önkénteskedni, jelentkezz újra!
</p>

<p>
    Reméljük, hamarosan újra látunk! :)
</p>