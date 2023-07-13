<?php

namespace backend\modules\mailer\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Crud;

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
class MailerTemplate extends Crud
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MailerTemplate the static model class
	 */
//	public static function model($className=__CLASS__)
//	{
//		return parent::model($className);
//	}

	/**
	 * @return string the associated database table name
	 */
	public static  function tableName()
	{
		return 'sm_template';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['name', 'required'],
			['name', 'unique'],
			[['name', 'description', 'from', 'subject'], 'string', 'max'=>255],
			[['body', 'alternative_body'], 'safe'],
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			[['id, name, description, from, subject, body, alternative_body'], 'safe', 'on'=>'search'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'from' => 'From',
			'subject' => 'Subject',
			'body' => 'Body',
			'alternative_body' => 'Alternative Body',
		);
	}

}