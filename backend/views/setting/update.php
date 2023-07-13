<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Setting $model
 */

$this->title = Yii::t(
        'admin',
        'Update {modelClass}: ',
        [
            'modelClass' => 'Setting',
        ]
    ) . ' ' . $model->section. '.' . $model->key;

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Update');

?>
<div class="setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
