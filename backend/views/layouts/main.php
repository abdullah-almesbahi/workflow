<?php

use backend\assets\AppAsset;
use backend\assets\RtlAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use kartik\icons\Icon;
use yii\helpers\Url;

Icon::map($this);

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
if (\Yii::$app->language == 'ar') :
    RtlAsset::register($this);
endif;
\backend\assets\PrintAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="Abdullah Al-Mesbahi" name="author" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="navbar-inner">
            <!-- BEGIN NAVIGATION HEADER -->
            <div class="header-seperation">
                <!-- BEGIN MOBILE HEADER -->
                <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
                    <li class="dropdown">
                        <a id="main-menu-toggle" href="#main-menu" class="">
                            <div class="iconset top-menu-toggle-white"></div>
                        </a>
                    </li>
                </ul>
                <!-- END MOBILE HEADER -->
                <!-- BEGIN LOGO -->
                <a href="<?= Yii::$app->homeUrl ?>">
                    <img src="<?= Yii::$app->request->baseUrl ?>/img/white-logo.png" class="logo" alt="" data-src="<?= Yii::$app->request->baseUrl ?>/img/white-logo.png" data-src-retina="<?= Yii::$app->request->baseUrl ?>/img/white-logo.png" height="50" />
                </a>
                <!-- END LOGO -->
                <!-- BEGIN LOGO NAV BUTTONS -->
                <ul class="nav pull-right notifcation-center">
                    <li class="dropdown" id="header_task_bar">
                        <a href="#" class="dropdown-toggle active" data-toggle="">
                            <i class="fa fa-home icon-18"></i>
                        </a>
                    </li>
                </ul>
                <!-- END LOGO NAV BUTTONS -->
            </div>
            <!-- END NAVIGATION HEADER -->
            <!-- BEGIN CONTENT HEADER -->
            <div class="header-quick-nav">
                <!-- BEGIN HEADER LEFT SIDE SECTION -->
                <div class="pull-left">
                    <!-- BEGIN SLIM NAVIGATION TOGGLE -->
                    <ul class="nav quick-section">
                        <li class="quicklinks">
                            <a href="#" class="" id="layout-condensed-toggle">
                                <i class="fa fa-bars font-size-18"></i>
                            </a>
                        </li>
                        <!-- <li class="quicklinks" style="  margin-top: -14px;">
                        <img src="<?= Yii::$app->request->baseUrl ?>/img/client-logo.png" class="logo" alt="" data-src="<?= Yii::$app->request->baseUrl ?>/img/client-logo.png" data-src-retina="<?= Yii::$app->request->baseUrl ?>/img/client-logo.png" height="40" />
                    </li> -->

                        <li class="quicklinks"><span class="h-seperate"></span></li>
                        <!-- BEGIN SEARCH BOX -->
                        <!--                    <li class="m-r-10 input-prepend inside search-form no-boarder">-->
                        <!--                        <span class="add-on"><i class="fa fa-search font-size-18"></i></span>-->
                        <!--                        <input name="" type="text" class="no-boarder" placeholder="--><? //= \Yii::t('admin','Search') 
                                                                                                                    ?>
                        <!--" style="width:250px;">-->
                        <!--                    </li>-->
                        <!-- END SEARCH BOX -->
                    </ul>
                    <!-- BEGIN HEADER QUICK LINKS -->
                </div>
                <!-- END HEADER LEFT SIDE SECTION -->
                <!-- BEGIN HEADER RIGHT SIDE SECTION -->
                <div class="pull-right">
                    <div class="chat-toggler hide">
                        <!-- BEGIN NOTIFICATION CENTER -->
                        <a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom" data-content="" data-toggle="dropdown" data-original-title="Notifications">
                            <div class="user-details">
                                <div class="username">
                                    <span class="badge badge-important">3</span>&nbsp;<span class="bold">&nbsp;<?= Yii::$app->user->identity->username ?></span>
                                </div>
                            </div>
                            <div class="iconset top-down-arrow"></div>
                        </a>
                        <div id="notification-list" style="display:none">
                            <div style="width:300px">
                                <!-- BEGIN NOTIFICATION MESSAGE -->
                                <div class="notification-messages info">
                                    <div class="user-profile">
                                        <!--                                    <img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">-->
                                    </div>
                                    <div class="message-wrapper">
                                        <div class="heading">Title of Notification</div>
                                        <div class="description">Description...</div>
                                        <div class="date pull-left">A min ago</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!-- END NOTIFICATION MESSAGE -->
                            </div>
                        </div>
                        <!-- END NOTIFICATION CENTER -->
                        <!-- BEGIN PROFILE PICTURE -->
                        <div class="profile-pic">
                            <img src="<?= Yii::$app->user->identity->gravatar(35) ?>" alt="Me" data-src="<?= Yii::$app->user->identity->gravatar(35) ?>" data-src-retina="<?= Yii::$app->user->identity->gravatar(158) ?>" width="35" height="35" />
                        </div>
                        <!-- END PROFILE PICTURE -->
                    </div>
                    <!-- BEGIN HEADER NAV BUTTONS -->
                    <ul class="nav quick-section">
                        <!-- BEGIN SETTINGS -->
                        <li class="quicklinks">
                            <a data-toggle="dropdown" class="dropdown-toggle pull-right" href="#" id="user-options">
                                <i class="fa fa-cog font-size-18"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-options">
                                <li><a href="<?= Yii::$app->urlManager->createUrl('/site/logout'); ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;<?= Yii::t('admin', 'logout'); ?></a></li>
                            </ul>
                        </li>
                        <!-- END SETTINGS -->
                    </ul>
                    <!-- END HEADER NAV BUTTONS -->
                </div>
                <!-- END HEADER RIGHT SIDE SECTION -->
            </div>
            <!-- END CONTENT HEADER -->
        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->

    <!-- BEGIN CONTENT -->
    <div class="page-container row-fluid">
        <!-- BEGIN SIDEBAR -->
        <!-- BEGIN MENU -->
        <div class="page-sidebar" id="main-menu">
            <div class="page-sidebar-wrapper" id="main-menu-wrapper">
                <!-- BEGIN MINI-PROFILE -->
                <div class="user-info-wrapper">
                    <div class="profile-wrapper">
                        <img src="<?= Yii::$app->user->identity->gravatar(69) ?>" alt="Me" data-src="<?= Yii::$app->user->identity->gravatar(69) ?>" data-src-retina="<?= Yii::$app->user->identity->gravatar(312) ?>" width="69" height="69" />
                    </div>
                    <div class="user-info">
                        <div class="greeting">Welcome</div>
                        <div class="username"><?= Yii::$app->user->identity->username ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <br /><br />
                <!-- END MINI-PROFILE -->
                <!-- BEGIN SIDEBAR MENU -->
                <?php
                echo backend\widgets\Menu::widget([
                    'items' => backend\models\BackendMenu::getAllMenu(),
                ]);
                ?>
                <!-- END SIDEBAR MENU -->

            </div>
        </div>
        <!-- BEGIN SCROLL UP HOVER -->
        <a href="#" class="scrollup">Scroll</a>
        <!-- END SCROLL UP HOVER -->
        <!-- END MENU -->
        <!-- BEGIN SIDEBAR FOOTER WIDGET -->
        <div class="footer-widget">
            <div class="pull-right">
                <a href="<?= Yii::$app->urlManager->createUrl('/site/logout'); ?>"><i class="fa fa-power-off"></i></a>
            </div>
        </div>
        <!-- END SIDEBAR FOOTER WIDGET -->
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE CONTAINER-->
        <div class="page-content">
            <div class="content">
                <!-- BEGIN BREADCRUMB -->
                <?= Breadcrumbs::widget([
                    'encodeLabels' => false,
                    'homeLink' => [
                        'label' => Yii::t('admin', 'Dashboard'),
                        'url' => Url::to(['/'])
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <!-- END BREADCRUMB -->

                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <i class="fa fa-arrow-circle-o-left"></i>
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <!-- END PAGE TITLE -->
                <!-- BEGIN PlACE PAGE CONTENT HERE -->
                <?= \common\widgets\Alert::widget() ?>
                <?= $content ?>
                <!-- END PLACE PAGE CONTENT HERE -->
            </div>
        </div>
        <!-- END PAGE CONTAINER -->
    </div>
    <!-- END CONTENT -->
    <?php $this->endBody() ?>
    <script type="text/javascript">
        $(function() {
            jQuery('[data-action="delete"],[data-action="af-delete"]').on('click', function(e) {
                if (confirm('<?= Yii::t('app', 'Are you sure you want to delete this object?') ?>')) {
                    jQuery.ajax({
                        'type': 'post',
                        'url': jQuery(this).attr('href')
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