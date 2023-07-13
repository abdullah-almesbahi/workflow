<?php

namespace backend\controllers;

use Yii;
use backend\models\AuditTrail;
use backend\models\AuditTrailSearch;
use backend\models\OriginSearch;
use backend\models\TransporterSearch;
use backend\models\ZoneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * LogController implements the CRUD actions for ActionLog model.
 */
class ReportController extends Controller
{

    /**
     * Lists all ActionLog models.
     * @return mixed
     */
    public function actionZone()
    {
        $searchModel = new ZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('zone', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionOrigin()
    {
        $searchModel = new OriginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('origin', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionTransporter()
    {
        $searchModel = new TransporterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('transporter', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new AuditTrail();
        $post = Yii::$app->request->post();
        if (sizeof($post) > 1) {
            $model->action    = 'SET';
            $model->stamp        = date('Y-m-d H:i:s'); // If we are storing a timestamp lets get one else lets get the date
            $model->user_id    = (string) Yii::$app->user->id; // Lets get the user id
        }

        if ($model->load($post) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Displays a single ActionLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the ActionLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ActionLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActionLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('admin', 'The requested page does not exist.'));
        }
    }
}
