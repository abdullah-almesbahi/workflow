<?php

namespace backend\modules\mailer\controllers;

use Yii;
use backend\modules\mailer\models\MailerQueue;

class CronController extends \yii\console\Controller {

    /**
     * Executes the command.
     * @param array $args command line parameters for this command.
     * @return int 0 on success, 1 on error.
     */
    public function actionIndex($args = array()) {
        try {
            $logger = Yii::getLogger();
            //$logger->autoFlush = 1;
            //$logger->detachEventHandler('onFlush', array(Yii::$app->log, 'collectLogs'));
            //$logger->attachEventHandler('onFlush', array($this, 'processLogs'));

            echo 'Limit: ' . Yii::$app->getModule('mailer')->sendEmailLimit . "\n";
            /**
             * @var $emails MailerQueue
             */

            $emails = MailerQueue::find()->where(['status' => MailerQueue::STATUS_NOT_SENT,])->limit(Yii::$app->getModule('mailer')->sendEmailLimit)->all();

            echo 'Count: ' . count($emails) . "\n";

            if (!$emails) {
                Yii::log(Yii::t('admin', 'No emails in queue. Exiting.'), 'warn', 'application.commands.MailerCommand');
                exit(0);
            }

            foreach ($emails as $email) {
                if (mail($email->to, $email->subject, $email->body, $email->headers)) {
                    $email->status = MailerQueue::STATUS_SENT;
                    $email->save();
                    echo 'Sent: ' . $email->to . "\n";
                }
            }
            exit(0);
        }
        catch (Exception $e) {
            Yii::log($e->getMessage(), 'error', 'application.commands.MailerCommand');
            exit(1);
        }
    }

    public function processLogs($event) {
        static $routes;
        $logger = Yii::getLogger();
        $routes = isset($routes) ? $routes : Yii::$app->log->getRoutes();
        foreach ($routes as $route) {
            if ($route->enabled) {
                $route->collectLogs($logger, true);
                $route->logs = array();
            }
        }
    }

    /**
     * Provides the command description.
     * This method may be overridden to return the actual command description.
     * @return string the command description. Defaults to 'Usage: php entry-script.php command-name'.
     */
    public function getHelp() {
        return 'Usage: yii mailer - without parameters';
    }

} 