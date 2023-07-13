<?php
$__url = \yii\helpers\Url::to(['find-by-plate-id']);
//$__url2 = \yii\helpers\Url::to(['update']);
if(isset($_GET['id'])) {
    $removeplate_id = $_GET['id'];
}else{
    $removeplate_id = 0;
}
echo <<< JS
jQuery(document).ready(function () {

    //on change dropdown
    jQuery('#removeplate-plate').change(function(){
        $.ajax({
            url: "$__url?id=" + jQuery('#removeplate-plate :selected').val(),
            dataType: "json"
        }).done(function( data ) {
            if(data.access == true){
                jQuery('#removeplate-stock_ids').html(' ');
                $.each(data.results,function( index, value ) {
                    console.log(value);
                    jQuery('#removeplate-stock_ids').append('<div class="checkbox"><label><input type="checkbox" name="Removeplate[stock_ids][]" value="'+value.id+'"> '+value.size+'</label></div>');
                });
            }else{
                jQuery('#removeplate-stock_ids').html(' ');
            }
        });
    });

    //onload
    if(jQuery('#removeplate-plate :selected').val().length > 0){
        $.ajax({
            url: "$__url?id=" + jQuery('#removeplate-plate :selected').val()+"&removeplate_id=$removeplate_id",
            dataType: "json"
        }).done(function( data ) {
            if(data.access == true){
                jQuery('#removeplate-stock_ids').html(' ');
                var checked = '';
                $.each(data.results,function( index, value ) {
                    if(value.checked == 'true'){
                        checked = 'checked';
                    }else{
                        checked = '';
                    }
                    jQuery('#removeplate-stock_ids').append('<div class="checkbox"><label><input type="checkbox" '+checked+' name="Removeplate[stock_ids][]" value="'+value.id+'"> '+value.size+'</label></div>');
                });
            }else{
                jQuery('#removeplate-stock_ids').html(' ');
            }
        });
    }
});

JS;
