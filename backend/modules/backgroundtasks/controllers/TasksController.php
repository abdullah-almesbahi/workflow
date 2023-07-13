<?php

namespace backend\modules\backgroundtasks\controllers;

use backend\modules\backgroundtasks\helpers\BackgroundTasks;
use backend\modules\backgroundtasks\models\NotifyMessage;
use backend\modules\backgroundtasks\models\Task;
//use app\models\Config;
use yii\console\Controller;

/**
 * Class TasksController
 * @package backend\modules\backgroundtasks\controllers
 */
class TasksController extends Controller
{

    public function actionIndex()
    {
        $now = time();

        /* @var $event Task[] */
        $event = Task::find()->where(
            [
                'type' => Task::TYPE_EVENT,
                'status' => Task::STATUS_ACTIVE,
            ]
        )->all();

        /* @var $repeat Task[] */
        $repeat = Task::find()->where(
            [
                'type' => Task::TYPE_REPEAT,
                'status' => Task::STATUS_ACTIVE,
            ]
        )->all();

        foreach ($event as $task) {
            $task->run();
        }

        foreach ($repeat as $task) {
            if (BackgroundTasks::isDue($task->ts , $now, $task->cron_expression)) {
                $task->setProcess();
            }
        }


        /* @var $process Task[] */
        $process = Task::find()->where(
            [
                'type' => Task::TYPE_REPEAT,
                'status' => Task::STATUS_PROCESS,
            ]
        )->all();

        foreach ($process as $task) {
            $task->run();
        }
    }

    /**
     * Clear notification messages older then set in config
     */
    public function actionClearOldNotifications()
    {
        $time = new \DateTime();
        $days = Config::getValue('errorMonitor.daysToStoreNotify', 28);
        $time->sub(new \DateInterval("P{$days}D"));
        NotifyMessage::deleteAll('UNIX_TIMESTAMP(`ts`) < ' . $time->getTimestamp());
    }

}
