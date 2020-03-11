<?php

namespace evolun\activity;

use Yii;

/**
 * Aktivitás modul
 *
 * Ez a modul trackeli a felhasználók aktivitását, és tartalmaz
 * konzol alkalmazásokat is, hogy el lehessen távolítani az inaktív felhasználókat
 *
 * Telepítés megjegyzések:
 * A konzol konfigban mindenképp be kell tölteni a module részhez, és meg kell adni a baseUrl-t is!
 */
class Module extends \yii\base\Module
{
    /**
     * A tábla, ahova rögzítjük az adatokat
     * @var string
     */
    public $userTable = '{{%user}}';

    /**
     * Az inaktivitás jelölő  mező (boolean)
     * @var string
     */
    public $inactiveField = 'inactive';

    /**
     * Az utolsó aktivitás dátumának mezője
     * @var string
     */
    public $lastActivityField = 'last_activity';

    /**
     * A felhasználó email címének mezője unique-nak kell lennie!
     * @var string
     */
    public $emailField = 'email';

    /**
     * A felhasználó név mezője
     * @var string
     */
    public $nameField = 'name';

    /**
     * Ennyi nap inaktivitás után küldünk figyelmeztető emailt
     * @var integer
     */
    public $inactiveWarningDays = 50;

    /**
     * Ennyi nap inaktivitás után töröljük a usert
     * @var integer
     */
    public $inactiveDeleteDays = 70;

    /**
     * Figyelmen kívül hagyja-e inaktivítás számolásnál a nyarat?
     * @var boolean
     */
    public $ignoreSummerTime = true;

    /**
     * Az inaktivitás figyelmeztető email
     * @var string
     */
    public $warningEmail = '@vendor/polgarz/evolun-activity/mail/{language}/inactive-warning';

    /**
     * A törlés email
     * @var string
     */
    public $deleteEmail = '@vendor/polgarz/evolun-activity/mail/{language}/inactive-delete';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();

        $this->warningEmail = strtr($this->warningEmail, ['{language}' => Yii::$app->language]);
        $this->deleteEmail = strtr($this->deleteEmail, ['{language}' => Yii::$app->language]);

        // ha konzolbol jovunk, mas a controller namespace, es nem traceklunk
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'evolun\activity\commands';
        } else {
            if (!Yii::$app->user->isGuest) {
                 Yii::$app->db->createCommand()
                    ->update(
                        $this->userTable,
                        [
                            $this->lastActivityField => new \yii\db\Expression('NOW()'),
                            $this->inactiveField => 0
                        ],
                        ['id' => Yii::$app->user->id]
                    )
                    ->execute();
            }
        }
    }

    public function registerTranslations()
    {
        if (!isset(Yii::$app->get('i18n')->translations['activity'])) {
            Yii::$app->get('i18n')->translations['activity*'] = [
                'class' => \yii\i18n\PhpMessageSource::className(),
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'activity' => 'activity.php',
                ]
            ];
        }
    }
}
