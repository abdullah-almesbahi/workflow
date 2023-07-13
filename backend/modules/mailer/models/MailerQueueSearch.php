<?php

namespace backend\modules\mailer\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Crud;
use backend\modules\mailer\models\MailerQueue;

/**
 * This is the model class for table "sm_template".
 *
 * The followings are the available columns in table 'sm_template':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $from
 * @property string $subject
 * @property string $body
 */
class MailerQueueSearch extends MailerQueue
{

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['status'], 'integer'],
            [['to'], 'string', 'max' => 255],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [['id, to, status'], 'safe', 'on' => 'search'],
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
	public function search($params)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.



        $query = MailerQueue::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
            ]
        );

        $query->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
	}
}