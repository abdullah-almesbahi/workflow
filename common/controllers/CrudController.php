<?php

namespace common\controllers;

use Yii;
use backend\models\Crud;
use backend\models\CrudSearch;
use backend\models\AF;
use backend\models\AFSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



/**
 * CrudController implements the CRUD actions for Crud model.
 */
class CrudController extends Controller
{

    public $table_name;
    public $instance;
    public $af;
    public $ruler_data;
    public $findModelHandler = null;
    public $createCrudClassHandler = null;
    public $updateExtraUpdateHandler = null;
    public $updateAjaxResponseHandler = null;

    /**
     * @param string $id the ID of this controller.
     * @param Module $module the module that this controller belongs to.
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public function __construct($id, $module, $config = [])
    {
        $this->id = $id;
        $this->module = $module;

        //Instance for the current table
        $this->table_name = strtolower($this->getTableName());
        $this->instance = new Crud();
        $this->instance->setTableName(strtolower($this->table_name));

        //Instance for additional field
        $this->af = new af();
        $this->af->setParentTableName(strtolower($this->table_name));

        $this->check_default_additional_fields();
    }

    public function check_default_additional_fields() {

        $columns = $this->instance->getTableSchema()->columns;

        $found = array();
        if (!empty($columns) && count($columns) > 0) {

            $count = 0;

            foreach ($columns as $k => $v) {

                //if column is exists in current table , enter
                if ($v->name == 'create_time' || $v->name == 'status' || $v->name == 'sort' || $v->name == 'id' ||
                    $v->name == 'title' || $v->name == 'update_time')
                {
                    $found[$v->name] = true;
                    $count++;
                }
                if(isset($found[$v->name])){
                    if ( false === $this->af->findByName($v->name) && ($v->name == 'title' ||  $v->name == 'status'  ) ) {
                        $result = $this->af->add_af($v);
                        //print_r($result);die();
                        continue;
                    }
                }else{
                    if (!$this->af->findByName($v->name)) {
                        $this->af->add_af($v);
                    }
                }
            }

            if ($count < 6) {
                $this->instance->alter_table($found);
            }
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'af-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Crud models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->createCrudSearchClass();
        $searchModel->setTableName(strtolower($this->getTableName()));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $af_columns = [];
        $where = [
            ['like','admin_display' , 'index'],
            ['like','admin_display' , key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))],
        ];
        $all = $this->af->getAll($where);
        if(count($all) > 0 && is_array($all) ) {
            foreach ($all as $k => $v) {
                $af_columns[$k] = array(
                    'attribute' => $v->attributes['name'],
                );
            }
        }

        return $this->render('@app/views/crud/index', [
            'af_columns' => $af_columns,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Crud model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('@app/views/crud/view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     *      * Updates an existing Crud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @param array $redirect
     * @return string
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id = null ,$redirect = ['index'] )
    {


        $model = null;
        if (null === $id || 0 === $id) {

            $model = $this->createCrudClass();
            $model->setTableName(strtolower($this->getTableName()));
            $model->loadDefaultValues();
        } else {
            $model = $this->findModel($id);
        }

        if (null === $model) {
            throw new ServerErrorHttpException;
        }

        $post = \Yii::$app->request->post();

        if ($model->load($post) && $model->validate() && $this->rulerValidate($model)) {

            $save_result = $model->save(false);

            if (isset($this->updateExtraUpdateHandler)) {
                if ($result = call_user_func($this->updateExtraUpdateHandler, $post)) {

                }
            }
            if ($save_result) {
                Yii::$app->session->setFlash('success', Yii::t('admin', 'Record has been saved'));
                if (Yii::$app->request->isAjax) {
                    if (isset($this->updateAjaxResponseHandler)) {
                        if ($result = call_user_func($this->updateAjaxResponseHandler, $post['Crud'])) {
                            die( Json::encode($result));
                        }
                    }
                    die( Json::encode([
                        'access'=>'true',
                        'data' => $post['Crud'],
                    ]));
                }
                return $this->redirect($redirect);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('admin', 'Cannot save data'));
            }
        }else {
            // validation failed: $errors is an array containing error messages
            $errors = $model->errors;
            if (is_array($errors) && count($errors) > 0) {
                foreach($errors as $key => $message) {
                    Yii::$app->session->addFlash('error', implode(', ' ,$message));
                }
            }
            $where = [
                ['like','admin_display' , 'update'],
                ['like','admin_display' , key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))],
            ];
            return $this->render('@app/views/crud/update', [
                'fields' => $this->af->getAll($where),
                'model' => $model,
            ]);
        }

    }

    /**
     * Deletes an existing Crud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Crud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * the model class can be override
     * @param integer $id
     * @return Crud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        //override the class if is defined
        $modelClass = 'Crud';
        if(isset(static::$modelClass)){
            $modelClass = static::$modelClass;
        }

        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('admin','The requested page does not exist.'));
        }

    }

    /**
     * @return class Crud
     */
    public function createCrudClass(){
        //override if there is another handler
        if (isset($this->createCrudClassHandler)) {
            if ($result = call_user_func($this->createCrudClassHandler)) {
                return $result;
            }
        }

        return new Crud();

    }

    /**
     * @return  class CrudSearch
     */
    public function createCrudSearchClass(){
        return new CrudSearch();
    }


    /**
     * Lists all Additional fields for CRUD models.
     * @return mixed
     */
    public function actionAf()
    {

        //$ff = $this->af->get_additional_fields();
        $searchModel = new AFSearch();
        $searchModel->setParentTableName(strtolower($this->table_name));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/views/crud/_af_index', [
//            'fields' => $ff,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Updates an existing Additonal Field model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionAfUpdate($id = null)
    {

        $model = null;
        if (null === $id) {
            $model = new AF();
            $model->loadDefaultValues();
            $model->table = strtolower($this->table_name);
        } else {
            $model =  $this->findAFModel($id);

        }
        $model->setParentTableName(strtolower($this->table_name));
        if (null === $model) {
            throw new ServerErrorHttpException;
        }

        $post = \Yii::$app->request->post();

        if ($model->load($post) && $model->validate()) {
            //if new
            if (null === $id) {
                $model->add_sql_field($model->attributes);
            }elseif($model->oldAttributes['sql_type'] != $model->attributes['sql_type']) {
                // handle change sql type
               $model->change_sql_field($model);
            }

            $model->display = !empty( $model->display)?join(', ',$model->display):'';
            $model->admin_display = !empty( $model->admin_display)?join(', ',$model->admin_display):'';

            $model->c_action = !empty( $model->c_action)?serialize($model->c_action):'';
            $model->c_if = !empty( $model->c_if)?serialize($model->c_if):'';
            $model->c_condition = !empty( $model->c_condition)?serialize($model->c_condition):'';
            $model->c_value = !empty( $model->c_value)?serialize($model->c_value):'';
            $model->c_table = !empty( $model->c_table)?serialize($model->c_table):'';
            $model->c_field = !empty( $model->c_field)?serialize($model->c_field):'';
            $model->c_option = !empty( $model->c_option)?serialize($model->c_option):'';
            $model->c_template = !empty( $model->c_template)?serialize($model->c_template):'';
            $model->c_user = !empty( $model->c_user)?serialize($model->c_user):'';

            $save_result = $model->save(false);
            if ($save_result) {
                Yii::$app->session->setFlash('success', Yii::t('admin', 'Record has been saved'));
                return $this->redirect(['af']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('admin', 'Cannot save data'));
            }
        }else {
            // validation failed: $errors is an array containing error messages
            $errors = $model->errors;

            if (is_array($errors) && count($errors) > 0) {
                foreach($errors as $key => $message) {
                    Yii::$app->session->addFlash('error', implode(', ' ,$message));
                }
            }else{

                $model->display = !empty($model->display) ? explode(', ', $model->display) : $model->display;
                $model->admin_display = !empty($model->admin_display) ? explode(', ', $model->admin_display) : $model->admin_display;

                $model->c_action = !empty($model->c_action) ? unserialize($model->c_action) : '';
                $model->c_if = !empty($model->c_if) ? unserialize($model->c_if) : '';
                $model->c_condition = !empty($model->c_condition) ? unserialize($model->c_condition) : '';
                $model->c_value = !empty($model->c_value) ? unserialize($model->c_value) : '';
                $model->c_table = !empty($model->c_table) ? unserialize($model->c_table) : '';
                $model->c_field = !empty($model->c_field) ? unserialize($model->c_field) : '';
                $model->c_option = !empty($model->c_option) ? unserialize($model->c_option) : '';
                $model->c_template = !empty($model->c_template) ? unserialize($model->c_template) : '';
                $model->c_user = !empty($model->c_user) ? unserialize($model->c_user) : '';
            }
            $tables = $model->getAllTables();
            $templates = \backend\modules\mailer\models\MailerTemplate::find()->select('id,name')->asArray()->all();

            return $this->render('@app/views/crud/_af_update', [
                'model' => $model,
                'tables' => $tables,
                'templates' => $templates,
            ]);
        }

    }

    /**
     * Deletes an existing Crud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAfDelete($id)
    {
        $model = $this->findAFModel($id);
        $this->af->drop_sql_field($model->attributes);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('admin', 'Record has been deleted'));
        return $this->redirect(['af']);
    }

    public function actionAfOrder( )   {
        $post = Yii::$app->request->post( );
        if (isset( $post['key'], $post['pos'] ))   {
            $this->findAFModel( $post['key'] )->order( $post['pos'] );
        }
    }

//    public function actionAfGetAllFields(){
//        if(empty( Yii::$app->request->post('table') )) {
//            return array(
//                'body' => date('Y-m-d H:i:s'),
//                'success' => false,
//            );
//        }
//        $result = AF::find()
//            ->select('name , title')
//            ->where(['table' => Yii::$app->request->post('table')])
//            ->asArray()
//            ->all();
//
//        $res = array(
//            'data' => \yii\helpers\ArrayHelper::map($result, 'name', 'title'),
//            'success' => true,
//        );
//
//        return \yii\helpers\Json::encode($res);
//
//    }

    protected function findAFModel($id)
    {
        if (($model = AF::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Returns the class name.
     * @return string the name of the class.
     */
    public function getTableName()
    {
        if(!empty($this->table_name))
        {
            return $this->table_name;
        }
        return str_replace('Controller' , '' , basename(get_called_class()));
    }

    public function rulerValidate ($model){
        $fields = AF::find()
            ->where(['table' => strtolower($this->table_name)])
            ->andWhere(['!=', 'c_option', ''])
            ->asArray()
            ->all();
        $rb = new \backend\lib\Ruler\RuleBuilder;
        if(is_array($fields) && count($fields) > 0){
            //loop all fields to validate
            foreach($fields as $k => $col){

                //only validate if this field allowed to be displayed in backend
                $col['admin_display'] = explode(', ',$col['admin_display']);

                if(in_array('update',$col['admin_display']) || in_array(key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())),$col['admin_display']) ){
                    $user_value = $model->attributes[$col['name']];
                    $c_if = unserialize($col['c_if']);
                    $c_condition = unserialize($col['c_condition']);
                    $c_value = unserialize($col['c_value']);

                    //loop actions
                    foreach($c_if as $i => $logic){

                        unset($operators,$values);
                        //loop conditions in action
                        foreach($c_condition[$i] as $ii => $condition){
                            $condition = '\backend\lib\Ruler\Operator\\'.ucfirst($condition);
                            $operators[] = new $condition(
                                new \backend\lib\Ruler\Variable($col['name'].'_user_'.$ii, $user_value),
                                new \backend\lib\Ruler\Variable($col['name'].'_name_'.$ii,$c_value[$i][$ii])
                            );
                        }

                        $logic = '\backend\lib\Ruler\Operator\\'.ucfirst($logic);
                        $rule = new \backend\lib\Ruler\Rule(
                            new  $logic($operators),
                            function() use ( $i , $col , $user_value , $model ) {

                                $c_action = unserialize($col['c_action']);
                                $c_option = unserialize($col['c_option']);

                                switch($c_action[$i]){
                                    case 'update_field':
                                        $c_table = unserialize($col['c_table']);
                                        $c_field = unserialize($col['c_field']);
                                        //update field in same table
                                        if($col['table'] == $c_table[$i]){
                                            //check if the value is method , then call it
                                            if(method_exists(get_class($this),$c_option[$i])){
                                                $function = $c_option[$i];
                                                $class = get_class($this);
                                                $user_value = $class::$function($user_value);
                                                $u = $c_field[$i];
                                                //updating database
                                                $model->$u = $user_value;
                                            }
                                        }

                                        break;
                                    case 'prevent':
                                        Yii::$app->session->addFlash('error', Yii::t('admin', $c_option[$i]));
                                        $this->ruler_data = false;
                                        break;
                                    case 'sms':
                                        break;
                                    case 'email':
                                        $c_template = unserialize($col['c_template']);
                                        $c_user = unserialize($col['c_user']);
                                        break;
                                }
                            }

                        );

                        $rule->execute(new \backend\lib\Ruler\Context());

                    }
                }
            }
            if(false === $this->ruler_data){
                return false;
            }
        }
        return true;
    }

}
