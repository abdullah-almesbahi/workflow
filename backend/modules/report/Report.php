<?php
namespace backend\modules\report;

class Report extends \yii\base\Module
{
	public $controllerNamespace = 'backend\modules\report\controllers';
	public $defaultRoute = 'report';
	static $default_settings  = [
		'customerIdForNoAccount' => 3,
	];
	public $settings;
	public function init()
	{
		parent::init();
	}
}