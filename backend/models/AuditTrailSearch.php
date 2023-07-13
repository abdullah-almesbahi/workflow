<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AuditTrail;

/**
 * ActionLogSearch represents the model behind the search form about `backend\models\ActionLog`.
 */
class AuditTrailSearch extends AuditTrail
{
    public function rules()
    {
        return [
            [['id', 'user_id' , 'model_id'], 'integer'],
            [['old_value', 'new_value', 'action', 'model', 'field', 'stamp'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AuditTrail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['stamp' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'model_id' => $this->model_id,
        ]);

        $query->andFilterWhere(['like', 'old_value', $this->old_value])
            ->andFilterWhere(['like', 'new_value', $this->new_value])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'field', $this->field])
            ->andFilterWhere(['like', 'stamp', $this->stamp]);

        return $dataProvider;
    }
}