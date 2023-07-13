<?php

use yii\helpers\Json;
use yii\helpers\Url;
\backend\assets\JsTreeAsset::register($this);
?>
<div id="<?= $id ?>"></div>

<?php
$data = Json::encode(Url::to($routes['edit']));
$url = Url::toRoute('index');
$script = <<< JS
jQuery(function(){
    jstree = jQuery("#$id").jstree($options);

    jstree.on('dblclick.jstree', function (e, data) {
        var object = jQuery(e.target).closest("a");
        document.location =  $data+ '?id=' + object.attr('data-id') + '&parent_id=' + object.attr('data-parent-id');
    });

    jQuery('#$id').on('activate_node.jstree', function (e, data) {
        jstree.jstree().save_state();
        document.location = '$url?parent_id=' + data.node.id;
    });
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);