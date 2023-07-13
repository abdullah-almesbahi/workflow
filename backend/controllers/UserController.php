<?php

namespace backend\controllers;

use backend\components\SearchModel;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\ActionLog;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CrudController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user manage'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel(
            [
                'model' => '\common\models\User',
                'partialMatchAttributes' => [
                    'username',
                    'email',
                    'create_time',
                ],
            ]
        );

        $dataProvider = $searchModel->search($_GET);
        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }

    /**
    * Updates or create an User model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id
    * @return mixed
    */
    public function actionUpdate($id = null ,$redirect = ['index'])
    {
        if (is_null($id)) {
            $model = new User(['scenario' => 'adminSignup']);
        } else {
            $model = $this->findModel($id);
            $model->scenario = 'admin';
        }
        $assignments = \Yii::$app->authManager->getAssignments($id);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $postAssignments = \Yii::$app->request->post('AuthAssignment', []);
            $errors = [];
            foreach ($assignments as $assignment) {
                $key = array_search($assignment->roleName, $postAssignments);
                if ($key === false) {
                    \Yii::$app->authManager->revoke(new Item(['name' => $assignment->roleName]), $model->id);
                } else {
                    unset($postAssignments[$key]);
                }
            }
            foreach ($postAssignments as $assignment) {
                try {
                    \Yii::$app->authManager->assign(new Item(['name' => $assignment]), $model->id);
                } catch (\Exception $e) {
                    $errors[] = 'Cannot assign "'.$assignment.'" to user';
                }
            }
            if (count($errors) > 0) {
                \Yii::$app->getSession()->setFlash('error', implode('<br />', $errors));
            }

            \Yii::$app->session->setFlash('success', \Yii::t('admin', 'Record has been saved'));
            //ActionLog::add(\Yii::t('admin', 'Record has been saved'),$model); //log message for success
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render(
                'update',
                [
                    'model' => $model,
                    'assignments' => ArrayHelper::map($assignments, 'roleName', 'roleName'),
                ]
            );
        }
    }

    /**
    * Deletes an existing User model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    */
    public function actionDelete($id,$redirect = true)
    {
        $model = $this->findModel($id);
        $model->scenario = 'admin';
        $model->status = User::STATUS_DELETED;
        $model->save(true, ['status']);
        return $this->redirect(['index']);
    }

    public function actionRemoveAll()
    {
        $items = \Yii::$app->request->post('items', []);
        if (!empty($items)) {
            $items = User::find()->where(['in', 'id', $items])->all();
            foreach ($items as $item) {
                $item->scenario = 'admin';
                $item->status = User::STATUS_DELETED;
                $item->save(true, ['status']);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionAddAssignment($id, $userId)
    {
        try {
            \Yii::$app->authManager->assign(new Item(['name' => $id]), $userId);
            $result = [
            'status' => 1,
            'message' => 'Success',
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => 0,
                'message' => \Yii::t('admin','Cannot add assignment'),
            ];
        }
        return Json::encode($result);
    }

    public function actionRemoveAssignment($id, $userId)
    {
        if (\Yii::$app->authManager->revoke(new Item(['name' => $id]), $userId)) {
            $result = [
                'status' => 1,
                'message' => \Yii::t('admin','Success'),
            ];
        } else {
            $result = [
            'status' => 0,
            'message' => \Yii::t('admin','Cannot remove assignment'),
            ];
        }
        return Json::encode($result);
    }

    /**
    * Finds the User model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return User the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    public  function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('admin','The requested page does not exist.'));
        }
    }
}
