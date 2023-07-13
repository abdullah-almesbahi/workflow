<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Inbox', 'url' => ['/site/index']],
                ['label' => 'Outbox', 'url' => ['/order/index']],
                ['label' => 'Order History', 'url' => ['/order/history']],
                ['label' => 'My Account', 'url' => ['/account/index']],
                ['label' => 'Billing', 'url' => ['/account/billing']],
                ['label' => 'Customer Service', 'url' => ['/site/customer-care']],
                ['label' => 'Rates', 'url' => ['/rate/index']],
    //                ['label' => 'About', 'url' => ['/site/about']],
    //                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => \Yii::t('app', 'Logout').' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= \common\widgets\Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    <script type="text/javascript">
        $(function(){
            jQuery('[data-action="delete"]').on('click', function(e){
                if (confirm('<?= Yii::t('app', 'Are you sure you want to delete this object?') ?>')) {
                    jQuery.ajax({
                        'type' : 'post',
                        'url' : jQuery(this).attr('href')
                    });
                }
                window.location.reload();
                return false;
            });
        });
    </script>
</body>
</html>
<?php $this->endPage() ?>
