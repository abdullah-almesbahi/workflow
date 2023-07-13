<?php

namespace backend\controllers;

use backend\actions\JSTreeGetTrees;
use backend\models\BackendMenu;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use devgroup\JsTreeWidget\AdjacencyFullTreeDataAction;

class BackendMenuController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if(\Yii::$app->user->identity->username == 'developer'){
                                return true;
                            }
                            return false;
                        },
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'getTree' => [
                'class' => AdjacencyFullTreeDataAction::className(),
                'class_name' => BackendMenu::className(),
                'model_label_attribute' => 'name',
                'vary_by_type_attribute' => null,
            ],
        ];
    }

    public function actionIndex($parent_id = 1)
    {
        $searchModel = new BackendMenu();
        $searchModel->parent_id = $parent_id;

        $params = Yii::$app->request->get();

        $dataProvider = $searchModel->search($params);

        $model = null;
        if ($parent_id > 0) {
            $model = BackendMenu::findOne($parent_id);
        }

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'model' => $model,
            ]
        );
    }

    public function actionEdit($parent_id = null, $id = null)
    {
        if (null === $parent_id) {
            throw new NotFoundHttpException;
        }

        /** @var null|BackendMenu|HasProperties $model */
        $model = null;
        if (null !== $id) {
            $model = BackendMenu::findById($id);
        } else {
            if (null !== $parent = BackendMenu::findById($parent_id)) {
                $model = new BackendMenu;
                $model->loadDefaultValues();
                $model->parent_id = $parent_id;

            } else {
                $model = new BackendMenu;
                $model->loadDefaultValues();
                $model->parent_id = 0;
            }
        }

        if (null === $model) {
            throw new ServerErrorHttpException;
        }

        $post = \Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('admin', 'Record has been saved'));
                return $this->redirect(
                    [
                        'backend-menu/index',
                        // 'id' => $model->id,
                        'parent_id' => $model->parent_id
                    ]
                );
            } else {
                throw new ServerErrorHttpException;
            }
        }

        return $this->render(
            'form',
            [
                'model' => $model,
            ]
        );
    }

    public function actionDelete($id = null, $parent_id = null)
    {

        if ((null === $id) || (null === $model = BackendMenu::findById($id))) {
            throw new NotFoundHttpException;
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('admin', 'The object is placed in the cart'));
        } else {
            Yii::$app->session->setFlash('success', Yii::t('admin', 'Object has been removed'));
        }

        return $this->redirect(Url::to(['index', 'parent_id' => $model->parent_id]));
    }

    public function actionRemoveAll($parent_id)
    {
        $items = Yii::$app->request->post('items', []);
        if (!empty($items)) {
            $items = BackendMenu::find()->where(['in', 'id', $items])->all();
            foreach ($items as $item) {
                $item->delete();
            }
        }

        return $this->redirect(['index', 'parent_id' => $parent_id]);
    }

    public function actionReorder(){

        if(isset($_POST['order'])){

            foreach($_POST['order'] as $id => $order){
                if(empty($order)){
                    continue;
                }
                $b = BackendMenu::findOne($id);
                $b->sort_order = $order;
                $b->save(false);
            }
        }
    }

    public function actionParentUpdate(){
        if(isset($_GET['parent_id'],$_GET['id'])){
            $b = BackendMenu::findOne($_GET['id']);
            $b->parent_id = $_GET['parent_id'];
            $b->save(false);
        }
    }
}
