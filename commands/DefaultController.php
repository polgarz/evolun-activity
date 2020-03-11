<?php
namespace evolun\activity\commands;

use Yii;
use yii\console\Controller;

class DefaultController extends Controller
{
    /**
     * Inaktiv userek eseten levelet kuld
     * @return void
     */
    public function actionSendInactiveWarningMessages()
    {
        // itt elkerjuk a latszolagos inaktiv usereket, hogy lecsekkoljuk a pontos inaktiv napokat
        $users = $this->getInactiveUsers();
        $module = Yii::$app->controller->module;

        foreach ($users as $user) {
            $lastActivity = new \DateTime($user[$module->lastActivityField]);
            $inactiveDays = $this->calculateInactiveDays($lastActivity, $module->ignoreSummerTime);

            // ha eleg ideje inaktiv, es meg nem allitottuk at inaktivra a statuszat
            if ($inactiveDays >= $module->inactiveWarningDays && !$user[$module->inactiveField]) {
                Yii::$app->mailer->compose($module->warningEmail, ['user' => $user])
                    ->setFrom(Yii::$app->params['mainEmail'])
                    ->setTo([$user[$module->emailField] => $user[$module->nameField]])
                    ->setSubject(Yii::t('activity', 'Your account will be deleted in our volunteering platform due inactivity!'))
                    ->send();

                // atallitjuk inaktivra
                Yii::$app->db->createCommand()
                    ->update(
                        $module->userTable,
                        [$module->inactiveField => 1],
                        [$module->emailField => $user['email']]
                    )
                    ->execute();
            }
        }
    }

    /**
     * Inaktiv felhasznalokat torol bizonyos inaktivitasi nap utan
     * @return void
     */
    public function actionDeleteInactiveUsers()
    {
        // itt elkerjuk a latszolagos inaktiv usereket, hogy lecsekkoljuk a pontos inaktiv napokat
        $users = $this->getInactiveUsers();
        $module = Yii::$app->controller->module;

        foreach ($users as $user) {
            $lastActivity = new \DateTime($user[$module->lastActivityField]);
            $inactiveDays = $this->calculateInactiveDays($lastActivity, $module->ignoreSummerTime);

            // ha eleg ideje inaktiv, es mar inaktiv, azaz figyelmeztettuk
            if ($inactiveDays >= $module->inactiveDeleteDays && $user[$module->inactiveField]) {
                Yii::$app->mailer->compose($module->deleteEmail, ['user' => $user])
                    ->setFrom(Yii::$app->params['mainEmail'])
                    ->setTo([$user[$module->emailField] => $user[$module->nameField]])
                    ->setSubject(Yii::t('activity', 'Your account has been deleted due to inactivity'))
                    ->send();

                // toroljuk a usert
                Yii::$app->db->createCommand()
                    ->delete($module->userTable, ['email' => $user[$module->emailField]])->execute();
            }
        }
    }

    /**
     * Visszaadja azokat a usereket, akik inaktivnak tunnek (x honapja nem aktivak legalabb)
     * @return array
     */
    private function getInactiveUsers()
    {
        $query = new \yii\db\Query();

        return $query->select('*')
            ->from(Yii::$app->controller->module->userTable)
            ->all();
    }

    /**
     * Kiszamolja az inkativ napok szamat, figyelembe veve, hogy nyaron nem kell aktivnak lennie
     * @param  string $lastActivity Utolso aktivitas datuma
     * @param  boolean $summertime Nyaron engedelyezett-e az inaktivitas?
     * @return void
     */
    private function calculateInactiveDays(\DateTime $lastActivity, $summertime = true)
    {
        $today = new \DateTime();
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($lastActivity, $interval, $today);
        $inactiveDays = 0;

        foreach ($dateRange as $date) {
            // ha nezzuk a nyarat
            if ($summertime) {
                // es a datum a nyarra esik, nem szamoljuk
                if ($date->format('md') <= '0615' || $date->format('md') >= '0901') {
                    $inactiveDays++;
                }
            } else {
                $inactiveDays++;
            }
        }

        return $inactiveDays;
    }
}
