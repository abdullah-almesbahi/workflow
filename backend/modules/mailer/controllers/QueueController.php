<?php

namespace backend\modules\mailer\controllers;

use Yii;
use backend\modules\mailer\models\MailerQueue;
use backend\modules\mailer\models\MailerQueueSearch;
use backend\controllers\CrudController;

class QueueController extends CrudController
{

    public $table_name = "sm_queue";


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}


	/**
	 * Redirection to 'admin'
	 */
	public function actionIndex() {
        $searchModel = new MailerQueueSearch();
        $searchModel->setTableName(strtolower($this->getTableName()));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $af_columns = [];
        foreach($this->af->getAll("admin_display like '%index%'") as $k => $v){
            $af_columns[$k] = array(
                'attribute' => $v->attributes['name'],
            );
        }

        return $this->render('@app/views/crud/index', [
            'af_columns' => $af_columns,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model = new MailerQueue('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['MailerQueue']))
			$model->attributes = $_GET['MailerQueue'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $id
	 * @throws CHttpException
	 * @return
	 * @internal param \the $integer ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model = MailerQueue::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'simple-mailer-queue-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
