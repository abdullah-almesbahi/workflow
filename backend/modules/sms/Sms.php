<?php
namespace backend\modules\sms;

class Sms extends \yii\base\Module
{

	/**
	 * @var string the default route of this module. Defaults to 'default'.
	 * The route may consist of child module ID, controller ID, and/or action ID.
	 * For example, `help`, `post/create`, `admin/post/create`.
	 * If action ID is not given, it will take the default value as specified in
	 * [[Controller::defaultAction]].
	 */
	public $defaultRoute = 'sms';

	public function init() {
        parent::init();
	}
}
