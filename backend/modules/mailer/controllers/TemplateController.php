<?php
namespace backend\modules\mailer\controllers;

use Yii;
use backend\modules\mailer\models\MailerTemplateSearch;
use backend\controllers\CrudController;


class TemplateController extends CrudController
{

    public $table_name = "sm_template";


    /**
     * Displays a particular model.
     * @param int $id
     * @return mixed|void
     * @throws CHttpException
     */
	public function actionView($id) {
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionPreview($id) {
		$model = $this->loadModel($id);
		echo $model->body;
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex() {
        $searchModel = new MailerTemplateSearch();
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param $id
     * @return mixed
     * @throws CHttpException
     */
	public function loadModel($id) {
		$model = MailerTemplate::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'simple-mailer-template-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSendPreview($email, $template, $id) {
		try {
			Mailer::send($email, $template, array(
				'__username__' => 'John Doe',
				'__activation_url__' => Yii::app()->request->hostInfo
					. '/registration/activation?user=johndoe&key=xxxxxxxxxxxxxxxxxxxxxxxxxx',
			));
			Yii::app()->user->setFlash('info', Yii::t('app', 'Mail was sent'));
			$this->redirect($this->createUrl('view', array(
				'id' => $id
			)));
		}
		catch (Exception $e) {
			throw new CHttpException(500, $e->getMessage());
		}
	}
}
