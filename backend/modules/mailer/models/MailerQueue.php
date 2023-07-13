<?php
namespace backend\modules\mailer\models;

use backend\models\Crud;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sm_queue".
 *
 * The followings are the available columns in table 'sm_queue':
 * @property integer $id
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $headers
 * @property integer $status
 */
class MailerQueue extends Crud
{
	const STATUS_NOT_SENT = 0;
	const STATUS_SENT = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SimpleMailQueue the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public static  function tableName() {
		return 'sm_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			[['to', 'subject', 'body', 'headers', 'status'], 'required'],
			[['status'], 'integer'],
			[['to'], 'string', 'max' => 255],
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			[['id', 'to', 'status'], 'safe', 'on' => 'search'],
		];
	}



	public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
	}

	/**
	 * @static
	 * @return int the number of mails not sent
	 */
	public static function getNotSentCount() {
		return (int)self::model()->countByAttributes(array(
			'status' => self::STATUS_NOT_SENT,
		));
	}

	/**
	 * @static
	 * @return int the number of sent mail
	 */
	public static function getSentCount() {
		return (int)self::model()->countByAttributes(
			array(
				'status' => self::STATUS_SENT,
			),
			'DATE(create_time)="' . date('Y-m-d') . '"'
		);
	}
}
