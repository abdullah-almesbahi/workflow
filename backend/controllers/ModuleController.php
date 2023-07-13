<?php

namespace backend\controllers;

use backend\models\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ModuleController extends CrudController
{

    public static $modelClass = '\backend\models\Module';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['settings manage'],
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
     * Adding moduler settings to the form
     * @param null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id = null,$redirect = ['index'])
    {
        Yii::$app->on('backend/crud/form/field/settings', function ($event) {
            echo $this->renderPartial('settings', [
                'model' => $event->sender->m,
            ]);
        });
        return parent::actionUpdate($id ,$redirect);
    }

    public function actionSettings($id)
    {
        $model = Module::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('admin', 'Not found'));
            return $this->redirect('/admin/modules');
        }

        if (Yii::$app->request->post('Settings')) {
            $model->setSettings(Yii::$app->request->post('Settings'));
            if($model->save()){
                $this->flash('success', Yii::t('admin', 'Module settings updated'));
            }
            else{
                $this->flash('error', Yii::t('admin', Yii::t('admin', 'Update error. {0}', $model->formatErrors())));
            }
            return $this->refresh();
        }
        else {

            return $this->render('settings', [
                'model' => $model
            ]);
        }
    }

    public function actionRestoresettings($id)
    {
        $model = Module::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('admin', 'Not found'));
        }
        else{
            $model->settings = '';
            $model->save();
            $this->flash('success', Yii::t('admin', 'Module default settings was restored'));
        }
        return $this->back();
    }

}