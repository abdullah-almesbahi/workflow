<?php
use yii\helpers\Html;

?>
Dimensions  : <b><?= str_replace('*','X',$model->dimesnsions) ?></b><br/>
Box Condition : <b><?= $model->condition ?></b><br/>
Comments : <b><?= $model->comments ?></b><br/><br/>
<?php
    $query = \frontend\models\ComplianceProduct::find()->where(['shipment_id' => $model->id]);
    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $query,
    ]);
    $af = new \backend\models\AF('complianceproduct');
    $all = $af->getAll(["display LIKE '%all_pages%'"]);
    $af_columns = [];
    if(count($all) > 0 && is_array($all) ) {
        foreach ($all as $k => $v) {
            $af_columns[] = array(
                'attribute' => $v->attributes['name'],
            );
        }
    }
    echo $this->render('@app/themes/default/crud/index', [
        'dataProvider' => $dataProvider,
        'af_columns' => $af_columns,
        'button' => false,
        'title' => false,
    ]);
?>
Total Shipment Declared Value  : $ 00 .<br/>
<?php $form = \kartik\form\ActiveForm::begin(['id' => 'form-product-'. $model->id]);?>
<?= $form->field($model, 'notes')->textarea(['rows' => 5])->label('Notes') ?>
<input type="hidden" name="id" value="<?=$model->id?>" />
<button class="btn btn-primary" type="button" onclick="productSubmit('form-product-<?=$model->id?>');">Save</button>
<?php
\kartik\form\ActiveForm::end();
