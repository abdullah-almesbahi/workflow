<?php
namespace backend\models;

use Yii;
use yii\base\Object;
use raoul2000\workflow\base\WorkflowException;
use yii\base\InvalidConfigException;
use raoul2000\workflow\source\file\WorkflowDefinitionLoader;

class PhpClassLoader extends WorkflowDefinitionLoader {
	/**
	 * yii2 alias name containing the namespace value to use to load definition provider class.
	 * If this alias is defined, it take precedence over the *namespace* configuration attribute.
	 */
	const NAMESPACE_ALIAS_NAME = '@workflowDefinitionNamespace';
	/**
	 * @var string namespace where workflow definition class are located
	 */
	public $namespace = 'backend\modules\shipping\models';

	/**
	 * 
	 * @param unknown $workflowId
	 * @param unknown $source
	 * @throws WorkflowException
	 */
	public function loadDefinition($workflowId, $source)
	{

		$wfClassname = $this->getClassname($workflowId);
        //ugly fast fix to get definition for first or second workflow
		$wfClassname = $this->namespace.ucfirst(Yii::$app->controller->id);

		$defProvider = null;

		try {
			$defProvider = Yii::createObject(['class' => $wfClassname]);
		} catch ( \ReflectionException $e) {
			throw new WorkflowException('failed to load workflow definition : '.$e->getMessage());
		}	
//		if( ! $defProvider instanceof IWorkflowDefinitionProvider ) {
//			throw new WorkflowException('Invalid workflow provider : class '.$wfClassname
//				.' doesn\'t implement \raoul2000\workflow\source\file\IWorkflowDefinitionProvider');
//		}

        if(strpos($workflowId, '1')) {
            return $this->parse($workflowId, $defProvider->getDefinition(), $source);
        }elseif(strpos($workflowId, '2')){
            return $this->parse($workflowId, $defProvider->getDefinition2(), $source);
        }else{
            return $this->parse($workflowId, $defProvider->getDefinition(), $source);
        }

	}
	
	/**
	 * Returns the complete name for the Workflow Provider class used to retrieve the definition of workflow $workflowId.
	 * The class name is built by appending the workflow id to the namespace parameter set for this source component.
	 *
	 * @param string $workflowId a workflow id
	 * @return string the full qualified class name used to provide definition for the workflow
	 */
	public function getClassname($workflowId)
	{
		return $this->getNameSpace() . '\\' . $workflowId;
	}	

	/**
	 * Returns the namespace value used to load the workflow definition provider class.
	 * If the alias with name self::NAMESPACE_ALIAS_NAME is found, it takes precedence over the configured *namespace* 
	 * attribute.
	 * @return string the namespace value
	 */
	public function getNameSpace()
	{
		$nsAlias = Yii::getAlias(self::NAMESPACE_ALIAS_NAME,false);
		
		return   $nsAlias === false ? $this->namespace : $nsAlias;
	}
}