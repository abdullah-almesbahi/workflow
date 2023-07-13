<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use kartik\icons\Icon;
use yii\helpers\Url;
Icon::map($this);

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="Abdullah Al-Mesbahi" name="author" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="error-body no-top lazy" data-original="<?= Yii::$app->request->baseUrl ?>/img/login-bg.jpg" style="background-image: url('<?= Yii::$app->request->baseUrl ?>/img/login-bg.jpg')">
<?php $this->beginBody() ?>
<div class="container">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->
    <?= $content ?>
    <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTENT -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
