<?php

namespace backend\modules\mailer\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Crud;
use backend\modules\mailer\models\MailerTemplate;

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
class MailerTemplateSearch extends MailerTemplate
{

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			[['name', 'description', 'from', 'subject'], 'string', 'max'=>255],
			[['body', 'alternative_body'], 'safe'],
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			[['id, name, description, from, subject, body, alternative_body'], 'safe', 'on'=>'search'],
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



        $query = MailerTemplate::find();

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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'body', $this->alternative_body]);

        return $dataProvider;
	}
}