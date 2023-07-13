<?php
namespace frontend\controllers;

use backend\models\AF;
use frontend\models\Address;
use Yii;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\base\Security;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;

/**
 * Address controller
 */
class RateController extends CrudController
{

    public function actionIndex(){

        $final_result = [
            'SV' => 'N/A',
            'EX' => 'N/A',
            'FDX' => 'N/A',
            'FDX_IE' => 'N/A',
        ];
        if(count(Yii::$app->request->post()) > 0) {
            $post = Yii::$app->request->post();
            if($post['d_unit'] == 'inch') {
                $dimensions_wieght = ($post['w'] > 0 && $post['h'] > 0 && $post['l'] > 0) ? (($post['w'] * $post['h'] * $post['l']) / 166) : 0;
            }else{
                $dimensions_wieght = ($post['w'] > 0 && $post['h'] > 0 && $post['l'] > 0) ? (( ($post['w']/2.54) * ($post['h']/2.54) * ($post['l']/2.54)) / 166) : 0;
            }

            if($post['w_unit'] == 'lb'){
                $wieght = ($post['weight'] > 0) ?$post['weight'] : 0;
            }else{
                $wieght = ($post['weight'] > 0) ?($post['weight']/0.453592) : 0;
            }

            $final_wieght = ($dimensions_wieght > $wieght)?ceil($dimensions_wieght):ceil($wieght);
            $results = (new \yii\db\Query())->select(['service_type', 'rate'])->from('rate')->where(['country_zone' => $post['country']])->andWhere(['weight' => $final_wieght])->all();
            $results = ArrayHelper::map($results,'service_type','rate');
            //if it was basic plan
            if($post['plan'] == 3 && count($results) > 0){
                foreach($results as $k => $v){
                    $results[$k] = ($v+$final_wieght);
                }
            }
            $final_result = array_merge($final_result,$results);
        }



        return $this->render('index',[
            'result' => $final_result
        ]);
    }

}
