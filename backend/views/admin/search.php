<?php
    /**
     * @var \yii\web\View $this
     * @var \backend\models\Page[] $pagelist
     * @var \yii\data\Pagination $pages
     */

?>
<?php if (count($list) > 0): ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>phone</th>
            <th>Mobile</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($list as $_list): ?>
            <tr>
                <td><a href="<?= \yii\helpers\Url::to(['shipping/cc/customer' , 'id' => $_list->id]) ?>"><?= $_list->id; ?></a></td>
                <td><?= $_list->username; ?></td>
                <td><?= $_list->first_name; ?></td>
                <td><?= $_list->last_name; ?></td>
                <td><?= $_list->email; ?></td>
                <td><?= $_list->phone; ?></td>
                <td><?= $_list->mobile; ?></td>
                <td><?= $_list->status; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($results->pageCount > 1): ?>
        <div class="pagination">
            <?=
            \backend\widgets\LinkPager::widget(
                [
                    'firstPageLabel' => '&laquo;&laquo;',
                    'lastPageLabel' => '&raquo;&raquo;',
                    'pagination' => $results,
                ]
            );
            ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <p class="no-results"><?= Yii::t('admin', 'No results found') ?></p>
<?php endif; ?>