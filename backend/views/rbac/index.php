<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $permissions
 * @var yii\data\ActiveDataProvider $roles
 * @var bool $isRules
 */

use backend\widgets\BackendWidget;
use backend\widgets\flushcache\FlushCacheButton;
use kartik\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = Yii::t('app', 'Rbac');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $this->beginBlock('buttonGroup'); ?>
<div class="btn-toolbar" role="toolbar">
    <div class="btn-group">
        <?=
            Html::a(
                Yii::t('app', 'Create Permission'),
                ['create', 'type' => \yii\rbac\Item::TYPE_PERMISSION],
                ['class' => 'btn btn-primary']
            )
        ?>
        <?=
            Html::a(
                Yii::t('app', 'Create Role'),
                ['create', 'type' => \yii\rbac\Item::TYPE_ROLE],
                ['class' => 'btn btn-success']
            )
        ?>
    </div>
    <?= Html::button(Yii::t('app', 'Delete selected'), ['class'=> 'btn btn-danger', 'id' => 'deleteItems']); ?>
</div>
<?php $this->endBlock(); ?>
<div class="user-index">
    <?php
        BackendWidget::begin(
            [
                'icon' => 'lock',
                'title'=> $this->title,
                'footer' => $this->blocks['buttonGroup'],
            ]
        );
    ?>
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Permissions'),
                    'content' => $this->render('_rbacGrid', ['data' => $permissions, 'isRules' => $isRules, 'id' => 'operations']),
                    'active' => true,
                ],
                [
                    'label' => Yii::t('app', 'Roles'),
                    'content' => $this->render('_rbacGrid', ['data' => $roles, 'isRules' => $isRules, 'id' => 'roles']),
                ],
            ],
        ]); ?>
    <?php BackendWidget::end(); ?>
</div>

<?php

$script = <<< JS
    /**
      * localStorage test, if not available,
      * feature rememberActiveState has no function
     */
    var hasStorage = (function() {
      try {
        window.localStorage.setItem('tabTest', 'tabTest');
        window.localStorage.removeItem('tabTest');
        return true;
      } catch (exception) {
        return false;
      }
    }());
    // get sluged dockument URL
    function getControllerId() {
        var currentUrl = document.URL;
        return currentUrl
                .toLowerCase()
                .replace(/ /g,'-')
                .replace(/[^\w-]+/g,'');
    }
    // set localstorage item for an element
    function setStorage(elem) {
        var activeTab     = jQuery(elem).attr("href");
        if (hasStorage) {
            window.localStorage.setItem("_bs_activeTab_" + getControllerId(), activeTab);
        }
    }
    // get this localstorage item
    function initialSelect() {
        if (hasStorage) {
            var activeTab = window.localStorage.getItem("_bs_activeTab_" + getControllerId());
            if (activeTab !== "") {
                jQuery("[href=" + activeTab + "]").tab("show");
            }
        }

    }
    jQuery(".nav.nav-tabs > li > a").on("click", function () {
        setStorage(this);
    });

    jQuery(window).on("load", function () {
       initialSelect();
    });
    $(function() {
        $('#deleteItems').on('click', function() {
            $.ajax({
                'url' : '/backend/rbac/remove-items',
                'type': 'post',
                'data': {
                    'items' : $('.grid-view').yiiGridView('getSelectedRows')
                },
                success: function(data) {
                    location.reload();
                }
            });
        });
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>