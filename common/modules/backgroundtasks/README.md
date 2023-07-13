To automatically run the tasks necessary to register in the following cron `* * * * * path/to/asgard/application/yii background/tasks`

To use the controller, you must create a descendant of `yii\console\Controller` and prescribe the task manager using the muzzle (if the task is periodic) or 
use a helper`common\backgroundtasks\helpers\BackgroundTasks::addTask($params)`(if a problem arises event)

Available options for the helper:

* name - the name of the task
* description - a description of the task
* action - route of action
* params - line parameters separated by spaces
* init_event - initiating event