<?php

namespace frontend\models;

use backend\models\AF;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Crud;

/**
 * CrudSearch represents the model behind the search form about `backend\models\Crud`.
 */
class CrudSearch extends Crud
{
    public $searchHandler = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $test = array();
        foreach( $this->getTableSchema()->columns as $name => $value){
            $test[] = array(
                array($name),
                is_integer($value->type)?'integer':'string'
            );
        }
        return array_merge([[['id'], 'integer']],$test);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($query, $params)
    {

        if (isset($this->searchHandler)) {
            call_user_func($this->searchHandler, $query);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $af = new AF();
        $af->setParentTableName(strtolower(self::$table_name));
        $rules = $af->getAll();
        $require = $integer = $number = $safe = $email = [];
        if(count($rules) > 0 && is_array($rules)){
            $query->andFilterWhere([
                'id' => $this->id,
            ]);
            foreach($rules as $v) {
                unset($name);
                $name = $v->attributes['name'];
                $query->andFilterWhere(['like', $name, $this->$name]);
            }
        }

        return $dataProvider;
    }
}
