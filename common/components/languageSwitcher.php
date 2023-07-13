<?php

namespace common\components;

use backend\modules\shipping\models\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;

class languageSwitcher extends Widget
{

    public $languages = [
        'en' => 'English',
        'ar' => 'عربي',
        'es' => 'ESPAñOL',
        'tr' => 'TüRKçE',
    ];

    public function init()
    {
        if(php_sapi_name() === 'cli')
        {
            return true;
        }

        parent::init();

        $cookies = Yii::$app->request->cookies;
        $languageNew = Yii::$app->request->get('lang');
        if($languageNew)
        {
            if(isset($this->languages[$languageNew]))
            {
                Yii::$app->language = $languageNew;

                $this->setLanguage($languageNew);
            }
        }
        elseif($cookies->has('lang'))
        {
            Yii::$app->language = $cookies->getValue('lang');
        }elseif(!Yii::$app->user->isGuest)
        {

            if(!empty(Yii::$app->user->identity->lang)) {
                Yii::$app->language = Yii::$app->user->identity->lang;
                $this->setLanguage(Yii::$app->user->identity->lang);
          }
// DOME: Error i had to comment the next part?!
//else{
//                $user = User::findOne(Yii::$app->user->id);
//                if($cookies->has('lang') && !empty($cookies->getValue('lang')) ){
//                    $user->lang = $cookies->getValue('lang');
//                }else{
//                    $user->lang = 'en';
//                }
//                $user->save(false);
//            }
        }

    }

    public function setLanguage($language){

        $_cookies = Yii::$app->response->cookies;
        $_cookies->add(new \yii\web\Cookie([
            'name' => 'lang',
            'value' => $language
        ]));
        if (!\Yii::$app->user->isGuest) {
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $user->lang = $language;
            $user->save(false);
        }
    }

    public function run(){
        $languages = $this->languages;
//        $current = $languages[Yii::$app->language];
        unset($languages[Yii::$app->language]);

        $items = [];
        foreach($languages as $code => $language)
        {
            $temp = [];
            $temp['label'] = $language;
            $temp['url'] = Url::current(['lang' => $code]);
            array_push($items, $temp);
        }


        if(
            Yii::$app->language == 'en'){
            echo '<li><a href="'.$items[0]['url'].'">'

                .'<img src="'.Yii::getAlias('@web').'/themes/workflow/images/icons/ar.png" height="12" alt="" width="18">&nbsp'
                .$items[0]['label']
                .'</a></li>';
        }
        else {

            echo '<li><a href="'.$items[0]['url'].'">'
                .'<img src="'.Yii::getAlias('@web').'/themes/workflow/images/icons/en.png" height="12" alt="" width="18">&nbsp'
                .$items[0]['label']
                .'</a></li>';
        }



//        echo ButtonDropdown::widget([
//            'label' => $current,
//
//            'dropdown' => [
//
//                'items' => $items,
//            ],
//        ]);
    }

}