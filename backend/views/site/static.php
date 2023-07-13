<?php
$color = explode(' ', 'red purple blue green black dark-blue light-blue light-red blend');
$i = 0;

foreach ($statues as $k => $status) {
    if (!isset($color[$i])) {
        $i = 0;
    }
?>
    <div class="col-md-2 col-sm-6 spacing-bottom-sm spacing-bottom pull-left">
        <div class="tiles <?= isset($color[$i]) ? $color[$i] : ''; ?> added-margin">
            <div class="tiles-body">
                <div class="tiles-title"> <?= isset($status['name']) ? $status['name'] : '';
                                            ?> </div>
                <div class="heading"> <span class="animate-number" data-value="<?= isset($status['count']) ? $status['count'] : 0; ?>" data-animation-duration="1200">0</span></div>

            </div>
        </div>
    </div>
<?php
    $i++;
}

?>