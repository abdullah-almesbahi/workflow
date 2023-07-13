<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use backend\components\ActionColumn;

/* @var $this yii\web\View */
$this->title = 'Upgrade Your Account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($plan->id == 3): ?>
    <p>You can at any time upgrade your account from Basic to Plus. Make sure Auto Pay is actiaved, or add the upgrade fee to your balance.</p>
    <a href="<?= \yii\helpers\Url::to('account/upgrade/plus') ?>" class="btn btn-primary">Upgrade to Plus</a>
    <?php else: ?>
        You are having the highest subscription level.
    <?php endif; ?>



</div>