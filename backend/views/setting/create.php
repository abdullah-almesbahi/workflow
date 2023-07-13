<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var pheme\settings\models\Setting $model
 */

$this->title = Yii::t(
    'admin',
    'Create {modelClass}',
    [
        'modelClass' => 'Setting',
    ]
);
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
