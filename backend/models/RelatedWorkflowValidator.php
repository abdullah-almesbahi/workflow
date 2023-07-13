<?php
namespace backend\models;

use yii\validators\Validator;
use raoul2000\workflow\validation\WorkflowScenario;

class RelatedWorkflowValidator extends Validator
{

	private $constraint;

	/**
	 * Set constraint array where keys are scenario for the secondary workflow, and
	 * values are array of scenario for the primary workflow that allow secondary transition.
	 * If value is TRUE, the secondary transition is allowed.
	 *
	 * @see \yii\validators\Validator::init()
	 */
	public function init()
	{
		parent::init();

		$this->constraint =  [
			// model can enter into secondary workflow no matter what is the primary workflow transition
			WorkflowScenario::enterWorkflow('TestWorkflow2') => true,

			WorkflowScenario::changeStatus('TestWorkflow2/success', 'TestWorkflow2/onHold') => [
				WorkflowScenario::changeStatus('TestWorkflow1/draft', 'TestWorkflow1/correction'),
				WorkflowScenario::changeStatus('TestWorkflow1/ready', 'TestWorkflow1/draft')
			],

			WorkflowScenario::changeStatus('TestWorkflow2/onHold', 'TestWorkflow2/success') => [
				WorkflowScenario::changeStatus('TestWorkflow1/correction', 'TestWorkflow1/ready')
			]
		];
	}
	/**
	 * Perform validation.
	 * WARNING : primary and secondary status attribute names are hard coded (to change if needed)
	 *
	 * @see \yii\validators\Validator::validateAttribute()
	 */
	public function validateAttribute($model, $attribute)
	{

		$w2scenarios=null;
		$w1scenarios=null;

		try {

			list(,$w2scenarios) = $model->getBehavior('w2')->_createTransitionItems($model->status_ex, true, false);
			if( $w2scenarios == null) {
				return; // no pending transition for w2 : validation succeeds
			}
		} catch (Exception $e) {
			$this->addError($model , $attribute, 'invalid transition on secondary workflow');
			return;
		}
		//print_r($model->getBehavior('w1')->_createTransitionItems($model->status, true, false));die();
		try {
			list(,$w1scenarios) = $model->getBehavior('w1')->_createTransitionItems($model->status, true, false);
		} catch (Exception $e) {
			$this->addError($model ,$attribute, 'invalid transition on primary workflow');
			return;
		}

		//print_r($w2scenarios);print_r($this->constraint);die();
		foreach ($w2scenarios as $w2sc) {
			//[enter workflow {TestWorkflow2}] => 1
			if (isset( $this->constraint[$w2sc])) {
				//print_r($this->constraint[$w2sc]);print_r($w1scenarios);
				if( $this->constraint[$w2sc] === true) {

					return;
				}elseif($w1scenarios === null &&  in_array($w1scenarios, $this->constraint[$w2sc])) {
					return;
				}else {
					foreach ($w1scenarios as $w1sc) {
						if( in_array($w1sc,$this->constraint[$w2sc])) {
							return;
						}
					}
				}
			}
		}

		// the constraint array does not contain secondary AND related primary transition
		// for the current model.

		$msgInfo= implode(', ',$w2scenarios);
		$this->addError($model ,$attribute, 'constraint failed : '. $msgInfo);
	}
}