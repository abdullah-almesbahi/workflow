<?php

namespace backend\modules\backgroundtasks\helpers;

use backend\modules\backgroundtasks\models\Task;
use yii\console\Exception;
use yii\helpers\Json;

/**
 * Class BackgroundTasks
 * @package backend\modules\backgroundtasks\helpers
 */
class BackgroundTasks
{

    /**
     * Check cron expression
     * Return true if the expression matches with current time
     * @param $timestamp
     * @param $cronExpr
     * @return mixed
     */
    public static function checkExpression($timestamp, $cronExpr)
    {
        $strtime = date('r', $timestamp);

        $time = explode(' ', date('i G j n w', strtotime($strtime)));
        $crontab = explode(' ', $cronExpr);
        //print_r($time);print_r($crontab);die();
        $expr = [];
        foreach ($crontab as $index => $val) {
            $values = preg_replace(
                [
                    '/^\*$/',
                    '/^(\d+)$/',
                    '/^(\d+)-(\d+)\/(\d+)$/',
                    '/^(\d+)-(\d+)$/',
                    '/^\*\/(\d+)$/'
                ],
                [
                    '(true)',
                    '('.$time[$index].' === $1)',
                    '((($1 <= '.$time[$index].') && ('.$time[$index].' <= $2)) ? ('.$time[$index].'%$3 === 0) : false)',
                    '($1 <= '.$time[$index].') && ('.$time[$index].' <= $2)', '('.$time[$index].'%$1 === 0)'
                ],
                explode(',', $val)
            );
            $expr[] = implode(' || ', $values);
        }

        if ($expr[2] !== '(true)' && $expr[4] !== '(true)') {
            $expr[2] = '('.$expr[2].' || '.$expr[4].')';
            unset($expr[4]);
        }
        //die($strtime."|".implode(' && ', $expr));
        return eval('return '.implode(' && ', $expr).';');
    }

    /**
     *      * Determine if the cron is due to run based on the current date or a
     * specific date.  This method assumes that the current number of
     * seconds are irrelevant, and should be called once per minute.
     * @param $oldDate
     * @param $now
     * @param $cronExpr
     * @return bool Returns TRUE if the cron is due to run or FALSE if not
     */
    public static function isDue($oldDate, $now ,  $cronExpr)
    {
        $cron = \Cron\CronExpression::factory($cronExpr);
        $next = $cron->getNextRunDate($oldDate)->format('Y-m-d H:i:s');;
        if($now >= strtotime($next)){
            return true;
        }
        return false;
    }

    /**
     * Add event task in database
     * @param $params
     * @return bool
     */
    public static function addTask($params, $options = [])
    {
        $task = new Task(['scenario' => 'event']);
        $task->load(['Task' => $params]);
        $task->type = Task::TYPE_EVENT;
        $task->initiator = \Yii::$app->user->id;

        if (!empty($options)) {
            $task->setOptions($options);
        }

        return $task->validate() && $task->save();
    }

    /**
     * Remove Task model
     * @param $id
     * @return int
     */
    public static function removeTask($id)
    {
        return Task::deleteAll('id = :id', [':id' => $id]);
    }

    /**
     * Set task as active
     * @param $id
     * @return int
     */
    public static function setActive($id)
    {
        return Task::updateAll(['status' => Task::STATUS_ACTIVE], 'id = :id', [':id' => $id]);
    }

    /**
     * Set task as stopped
     * @param $id
     * @return int
     */
    public static function setStopped($id)
    {
        return Task::updateAll(['status' => Task::STATUS_STOPPED], 'id = :id', [':id' => $id]);
    }

    /**
     * Get Task data
     * @param $id
     * @param $controller
     * @return mixed
     * @throws Exception
     */
    public static function getData($id, $controller)
    {
        $task = Task::findOne($id);
        if ($task !== null && $task->action == $controller->route) {
            return Json::decode($task->data, true);
        } else {
            throw new Exception("Data not found");
        }
    }
}
