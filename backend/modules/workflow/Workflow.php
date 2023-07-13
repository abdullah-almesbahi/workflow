<?php
namespace backend\modules\workflow;

class Workflow extends \yii\base\Module
{
	public $attachImages = true;
	public $sendEmailLimit = 500;

	public function init() {
        parent::init();
	}
}
