<?php
/* @var $this yii\web\View */

$this->title = Yii::t('admin', 'Dashboard');

$workflows = [
    'plate' => [
        'title' => 'تركيب لوحة',
        'class' => '\backend\models\Plate',
    ],
    'removeplate' => [
        'title' => 'ازالة لوحة',
        'class' => '\backend\models\Removeplate',
    ],
    'maint' => [
        'title' => 'صيانة لوحة',
        'class' => '\backend\models\Maint',
    ],
    'stock' => [
        'title' => 'المخزن',
        'class' => '\backend\models\Stock',
    ],
];


//get title & status
foreach ($workflows as $table => $workflow) {
    $status = (new \backend\models\AF($table))->getStatusField();
    preg_match_all('/^\s*(.*?)\s*\|\s*(.+?)\s*(|\|(.+?))\s*$/m', $status['options'], $_regs);
    $statues = array();
    foreach ($_regs[1] as $__i => $__k) {
        $statues[$__k]['name'] = $_regs[2][$__i];
    }
    $results = $workflow['class']::find()
        ->select(['COUNT(status) AS cnt', 'status'])
        ->groupBy(['status'])
        ->all();
    foreach ($results as $count) {
        $statues[$count->status]['count'] = $count->cnt;
    }

    $items[] = [
        'label' => $workflow['title'],
        'content' => $this->render('static', ['statues' => $statues]),
        'headerOptions' => ['class' => 'pull-left'],
    ];
}
echo \yii\bootstrap\Tabs::widget([
    'items' => $items,
]);


$script = <<< JS

(function($) {
    $.fn.animateNumbers = function(stop, commas, duration, ease) {
        return this.each(function() {
            var hello = $(this);
            var start = parseInt(hello.text().replace(/,/g, ""));
			commas = (commas === undefined) ? true : commas;
            $({value: start}).animate({value: stop}, {
            	duration: duration == undefined ? 1000 : duration,
            	easing: ease == undefined ? "swing" : ease,
            	step: function() {
            		hello.text(Math.floor(this.value));
					if (commas) { hello.text(hello.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")); }
            	},
            	complete: function() {
            	   if (parseInt(hello.text()) !== stop) {
            	       hello.text(stop);
					   if (commas) { hello.text(hello.text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")); }
            	   }
            	}
            });
        });
    };
})(jQuery);

$(document).ready(function () {
    $('.animate-number').each(function () {
        $(this).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-animation-duration"), 10));
    });
});




JS;
$this->registerJs($script, \yii\web\View::POS_END);
