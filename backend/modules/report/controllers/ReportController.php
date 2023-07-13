<?php
namespace backend\modules\report\controllers;

use backend\controllers\CrudController;
use backend\models\User;
use backend\modules\report\models\Report;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

class ReportController extends CrudController
{
    public function actionList()
    {
        $query = Report::find()->orderBy(['create_time' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $af_columns = [
            [
                'attribute' => 'title',
                'label' => \Yii::t('admin' , 'Report'),
                'format' => 'raw',
                'value'=>function ($model, $key, $index, $column) {
                    $out = Html::a($model->title , ['report/parameters' , 'id' => $model->id]);
                    $out .= "<p>{$model->description}</p>";
                    return $out;
                },

            ],
        ];

        return $this->render('list' ,[
            'dataProvider' => $dataProvider,
            'af_columns' => $af_columns,
        ]);
    }

    public function actionParameters($id)
    {
        $report = Report::findOne($id);
        $conditions = $report->conditions;

            $paramConditions = array();
            foreach($conditions as $condition){
                if($condition->is_parametrizable == 1){
                    $paramConditions[] = $condition;
                }
            }

//            $ot = ObjectTypes::findById($report->getReportObjectTypeId());
            $ot = $report->table;
//            eval('$managerInstance = ' . $ot->getHandlerClass() . "::instance();");
//            $externalCols = $managerInstance->getExternalColumns();
            $externalCols = $report->columns;
            $externalFields = array();
            foreach($externalCols as $extCol){
                $externalFields[$extCol->field_name] = $this->get_ext_values($extCol->field_name);
            }
//            $params = array_var($_GET, 'params');
            $post = \Yii::$app->request->post();
            if(count($paramConditions) > 0 && !isset($post['Condition'])){
                $dataProvider = new ActiveDataProvider(['query' => $report->getConditions()->where(['is_parametrizable' => 1])]);
                return $this->render('parameters' ,[
                    'model' => $report,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                $dataProvider = new ActiveDataProvider(['query' => $report->getConditions()->where(['is_parametrizable' => 1])]);
                return $this->render('report_wrapper' ,[
                    'model' => $report,
                    'dataProvider' => $dataProvider,
                ]);

//                tpl_assign('template_name', 'view_custom_report');
//                tpl_assign('title', $report->getObjectName());
//                tpl_assign('genid', gen_id());
//                $parameters = '';
//                if(isset($params)){
//                    foreach($params as $id => $value){
//                        $parameters .= '&params['.$id.']='.$value;
//                    }
//                }
//                tpl_assign('parameterURL', $parameters);
////                $offset = array_var($_GET, 'offset');
////                if(!isset($offset)) $offset = 0;
////                $limit = array_var($_GET, 'limit');
////                if(!isset($limit)) $limit = 50;
//                $order_by = array_var($_GET, 'order_by');
//                if(!isset($order_by)) $order_by = '';
//                tpl_assign('order_by', $order_by);
//                $order_by_asc = array_var($_GET, 'order_by_asc');
//                if(!isset($order_by_asc)) $order_by_asc = null;
//                tpl_assign('order_by_asc', $order_by_asc);
//
//                $results = Reports::executeReport($report_id, $params, $order_by, $order_by_asc, $offset, $limit);
//                if(!isset($results['columns'])) $results['columns'] = array();
//                tpl_assign('columns', $results['columns']);
//                tpl_assign('db_columns', $results['db_columns']);
//                if(!isset($results['rows'])) $results['rows'] = array();
//                tpl_assign('rows', $results['rows']);
//                if(!isset($results['pagination'])) $results['pagination'] = '';
//                tpl_assign('pagination', $results['pagination']);
//                tpl_assign('types', self::get_report_column_types($report_id));
//                tpl_assign('post', $params);
//                $ot = ObjectTypes::findById($report->getReportObjectTypeId());
//                tpl_assign('model', $ot->getHandlerClass());
//                tpl_assign('description', $report->getDescription());
//                tpl_assign('conditions', $conditions);
//                tpl_assign('parameters', $params);
//                tpl_assign('id', $report_id);
//                tpl_assign('to_print', false);
            }






    }

    private function get_ext_values($field, $manager = null){
        $values = array(array('id' => '', 'name' => '-- ' . \Yii::t('admin','select') . ' --'));
        if($field == 'assign_id' || $field == 'customer_id' || $field == 'user_id' ){
            $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
            foreach($users as $user){
                $values[] = array('id' => $user->id , 'name' => $user->username);
            }
        }
        return $values;
    }

    // ---------------------------------------------------
    //  Custom Reports
    // ---------------------------------------------------

    function add_custom_report(){
        if (logged_user()->isGuest()) {
            flash_error(lang('no access permissions'));
            ajx_current("empty");
            return;
        }
        tpl_assign('url', get_url('reporting', 'add_custom_report'));
        $report_data = array_var($_POST, 'report');
        if(is_array($report_data)){
            tpl_assign('report_data', $report_data);
            $conditions = array_var($_POST, 'conditions');
            if(!is_array($conditions)) {
                $conditions = array();
            }
            tpl_assign('conditions', $conditions);
            $columns = array_var($_POST, 'columns');
            if(is_array($columns) && count($columns) > 0){
                tpl_assign('columns', $columns);
                $newReport = new Report();

                $member_ids = json_decode(array_var($_POST, 'members'));

                $notAllowedMember = '';
                if(!logged_user()->isManager() && !logged_user()->isAdminGroup() && !$newReport->canAdd(logged_user(), active_context(), $notAllowedMember )) {
                    if (str_starts_with($notAllowedMember, '-- req dim --')) flash_error(lang('must choose at least one member of', str_replace_first('-- req dim --', '', $notAllowedMember, $in)));
                    else trim($notAllowedMember) == "" ? flash_error(lang('you must select where to keep', lang('the report'))) : flash_error(lang('no context permissions to add', lang("report"), $notAllowedMember ));
                    ajx_current("empty");
                    return;
                }

                $newReport->setObjectName($report_data['name']);
                $newReport->setDescription($report_data['description']);
                $newReport->setReportObjectTypeId($report_data['report_object_type_id']);
                $newReport->setOrderBy($report_data['order_by']);
                $newReport->setIsOrderByAsc($report_data['order_by_asc'] == 'asc');
                $newReport->setIgnoreContext(array_var($report_data, 'ignore_context') == 'checked');

                try{
                    DB::beginWork();
                    $newReport->save();
                    $allowed_columns = $this->get_allowed_columns($report_data['report_object_type_id'], true);
                    foreach($conditions as $condition){
                        if($condition['deleted'] == "1") continue;
                        foreach ($allowed_columns as $ac){
                            if ($condition['field_name'] == $ac['id']){
                                $newCondition = new ReportCondition();
                                $newCondition->setReportId($newReport->getId());
                                $newCondition->setCustomPropertyId($condition['custom_property_id']);
                                $newCondition->setFieldName($condition['field_name']);
                                $newCondition->setCondition($condition['condition']);

                                $condValue = array_key_exists('value', $condition) ? $condition['value'] : '';
                                if($condition['field_type'] == 'boolean'){
                                    $newCondition->setValue(array_key_exists('value', $condition) ? '1' : '0');
                                }else if($condition['field_type'] == 'date'){
                                    if ($condValue != '') {
                                        $dtFromWidget = DateTimeValueLib::dateFromFormatAndString(user_config_option('date_format'), $condValue);
                                        $newCondition->setValue(date("m/d/Y", $dtFromWidget->getTimestamp()));
                                    }
                                }else{
                                    $newCondition->setValue($condValue);
                                }
                                $newCondition->setIsParametrizable(isset($condition['is_parametrizable']));
                                $newCondition->save();
                            }
                        }
                    }

                    asort($columns); //sort the array by column order
                    foreach($columns as $column => $order){
                        if ($order > 0) {
                            $newColumn = new ReportColumn();
                            $newColumn->setReportId($newReport->getId());
                            if(is_numeric($column)){
                                $newColumn->setCustomPropertyId($column);
                            }else{
                                $newColumn->setFieldName($column);
                            }
                            $newColumn->save();
                        }
                    }

                    $no_need_to_add_to_members = count($member_ids) == 0 && (logged_user()->isManager() || logged_user()->isAdminGroup());
                    if (!$no_need_to_add_to_members) {
                        $object_controller = new ObjectController();
                        $object_controller->add_to_members($newReport, $member_ids);
                    } else {
                        $newReport->addToSharingTable();
                    }

                    DB::commit();
                    flash_success(lang('custom report created'));
                    ajx_current('back');
                }catch(Exception $e){
                    DB::rollback();
                    flash_error($e->getMessage());
                    ajx_current("empty");
                }
            }
        }
        $selected_type = array_var($_GET, 'type', '');

        $types = array(array("", lang("select one")));
        $object_types = ObjectTypes::getAvailableObjectTypes();

        foreach ($object_types as $ot) {
            $types[] = array($ot->getId(), lang($ot->getName()));
        }
        if ($selected_type != '')
            tpl_assign('allowed_columns', $this->get_allowed_columns($selected_type));

        tpl_assign('object_types', $types);
        tpl_assign('selected_type', $selected_type);
        $new_report = new Report();
        tpl_assign('object', $new_report);
    }

    function edit_custom_report(){
        if (logged_user()->isGuest()) {
            flash_error(lang('no access permissions'));
            ajx_current("empty");
            return;
        }
        $report_id = array_var($_GET, 'id');
        $report = Reports::getReport($report_id);

        if(!$report->canEdit(logged_user())) {
            flash_error(lang('no access permissions'));
            ajx_current("empty");
            return;
        } // if

        if(is_array(array_var($_POST, 'report'))) {
            try{
                ajx_current("empty");
                $report_data = array_var($_POST, 'report');

                $member_ids = json_decode(array_var($_POST, 'members'));

                DB::beginWork();
                $report->setObjectName($report_data['name']);
                $report->setDescription($report_data['description']);
                $report->setReportObjectTypeId($report_data['report_object_type_id']);
                $report->setOrderBy($report_data['order_by']);
                $report->setIsOrderByAsc($report_data['order_by_asc'] == 'asc');
                $report->setIgnoreContext(array_var($report_data, 'ignore_context') == 'checked');

                $report->save();

                $conditions = array_var($_POST, 'conditions');
                if (!is_array($conditions)) {
                    $conditions = array();
                }

                foreach($conditions as $condition){
                    $newCondition = new ReportCondition();
                    if($condition['id'] > 0){
                        $newCondition = ReportConditions::getCondition($condition['id']);
                    }
                    if($condition['deleted'] == "1"){
                        $newCondition->delete();
                        continue;
                    }
                    $newCondition->setReportId($report_id);
                    $custom_prop_id = isset($condition['custom_property_id']) ? $condition['custom_property_id'] : 0;
                    $newCondition->setCustomPropertyId($custom_prop_id);
                    $newCondition->setFieldName($condition['field_name']);
                    $newCondition->setCondition($condition['condition']);
                    if($condition['field_type'] == 'boolean'){
                        $newCondition->setValue(isset($condition['value']) && $condition['value'] ? '1' : '0');
                    }else if($condition['field_type'] == 'date'){
                        if (array_var($condition, 'value') == '') $newCondition->setValue('');
                        else {
                            $dtFromWidget = DateTimeValueLib::dateFromFormatAndString(user_config_option('date_format'), $condition['value']);
                            $newCondition->setValue(date("m/d/Y", $dtFromWidget->getTimestamp()));
                        }
                    }else{
                        $newCondition->setValue(isset($condition['value']) ? $condition['value'] : '');
                    }
                    $newCondition->setIsParametrizable(isset($condition['is_parametrizable']));
                    $newCondition->save();
                }
                ReportColumns::delete('report_id = ' . $report_id);
                $columns = array_var($_POST, 'columns');

                asort($columns); //sort the array by column order
                foreach($columns as $column => $order){
                    if ($order > 0) {
                        $newColumn = new ReportColumn();
                        $newColumn->setReportId($report_id);
                        if(is_numeric($column)){
                            $newColumn->setCustomPropertyId($column);
                        }else{
                            $newColumn->setFieldName($column);
                        }
                        $newColumn->save();
                    }
                }

                $object_controller = new ObjectController();
                $object_controller->add_to_members($report, $member_ids);

                DB::commit();
                flash_success(lang('custom report updated'));
                ajx_current('back');
            } catch(Exception $e) {
                DB::rollback();
                flash_error($e->getMessage());
                ajx_current("empty");
            } // try
        }else{
            $this->setTemplate('add_custom_report');
            tpl_assign('url', get_url('reporting', 'edit_custom_report', array('id' => $report_id)));
            if($report instanceof Report){
                tpl_assign('id', $report_id);
                $report_data = array(
                    'name' => $report->getObjectName(),
                    'description' => $report->getDescription(),
                    'report_object_type_id' => $report->getReportObjectTypeId(),
                    'order_by' => $report->getOrderBy(),
                    'order_by_asc' => $report->getIsOrderByAsc(),
                    'ignore_context' => $report->getIgnoreContext(),
                );
                tpl_assign('report_data', $report_data);
                $conditions = ReportConditions::getAllReportConditions($report_id);
                tpl_assign('conditions', $conditions);
                $columns = ReportColumns::getAllReportColumns($report_id);
                $colIds = array();
                foreach($columns as $col){
                    if($col->getCustomPropertyId() > 0){
                        $colIds[] = $col->getCustomPropertyId();
                    }else{
                        $colIds[] = $col->getFieldName();
                    }
                }
                tpl_assign('columns', $colIds);
            }

            $selected_type = $report->getReportObjectTypeId();

            $types = array(array("", lang("select one")));
            $object_types = ObjectTypes::getAvailableObjectTypes();

            foreach ($object_types as $ot) {
                $types[] = array($ot->getId(), lang($ot->getName()));
            }

            tpl_assign('object_types', $types);
            tpl_assign('selected_type', $selected_type);
            tpl_assign('object', $report);

            tpl_assign('allowed_columns', $this->get_allowed_columns($selected_type), true);
        }
    }

    function view_custom_report(){
        $report_id = array_var($_GET, 'id');
        if (array_var($_GET, 'replace')) {
            ajx_replace();
        }
        tpl_assign('id', $report_id);
        if(isset($report_id)){
            $report = Reports::getReport($report_id);
            $conditions = ReportConditions::getAllReportConditions($report_id);
            $paramConditions = array();
            foreach($conditions as $condition){
                if($condition->getIsParametrizable()){
                    $paramConditions[] = $condition;
                }
            }

            $ot = ObjectTypes::findById($report->getReportObjectTypeId());
            eval('$managerInstance = ' . $ot->getHandlerClass() . "::instance();");
            $externalCols = $managerInstance->getExternalColumns();
            $externalFields = array();
            foreach($externalCols as $extCol){
                $externalFields[$extCol] = $this->get_ext_values($extCol, $report->getReportObjectTypeId());
            }
            $params = array_var($_GET, 'params');
            if(count($paramConditions) > 0 && !isset($params)){
                $this->setTemplate('custom_report_parameters');
                tpl_assign('model', $report->getReportObjectTypeId());
                tpl_assign('title', $report->getObjectName());
                tpl_assign('description', $report->getDescription());
                tpl_assign('conditions', $paramConditions);
                tpl_assign('external_fields', $externalFields);
            }else{
                $this->setTemplate('report_wrapper');
                tpl_assign('template_name', 'view_custom_report');
                tpl_assign('title', $report->getObjectName());
                tpl_assign('genid', gen_id());
                $parameters = '';
                if(isset($params)){
                    foreach($params as $id => $value){
                        $parameters .= '&params['.$id.']='.$value;
                    }
                }
                tpl_assign('parameterURL', $parameters);
                $offset = array_var($_GET, 'offset');
                if(!isset($offset)) $offset = 0;
                $limit = array_var($_GET, 'limit');
                if(!isset($limit)) $limit = 50;
                $order_by = array_var($_GET, 'order_by');
                if(!isset($order_by)) $order_by = '';
                tpl_assign('order_by', $order_by);
                $order_by_asc = array_var($_GET, 'order_by_asc');
                if(!isset($order_by_asc)) $order_by_asc = null;
                tpl_assign('order_by_asc', $order_by_asc);
                $results = Reports::executeReport($report_id, $params, $order_by, $order_by_asc, $offset, $limit);
                if(!isset($results['columns'])) $results['columns'] = array();
                tpl_assign('columns', $results['columns']);
                tpl_assign('db_columns', $results['db_columns']);
                if(!isset($results['rows'])) $results['rows'] = array();
                tpl_assign('rows', $results['rows']);
                if(!isset($results['pagination'])) $results['pagination'] = '';
                tpl_assign('pagination', $results['pagination']);
                tpl_assign('types', self::get_report_column_types($report_id));
                tpl_assign('post', $params);
                $ot = ObjectTypes::findById($report->getReportObjectTypeId());
                tpl_assign('model', $ot->getHandlerClass());
                tpl_assign('description', $report->getDescription());
                tpl_assign('conditions', $conditions);
                tpl_assign('parameters', $params);
                tpl_assign('id', $report_id);
                tpl_assign('to_print', false);
            }

            ApplicationReadLogs::createLog($report, ApplicationReadLogs::ACTION_READ);
        }
    }

    function view_custom_report_print(){
        $this->setLayout("html");
        set_time_limit(0);
        $params = json_decode(str_replace("'",'"', array_var($_POST, 'post')),true);

        $report_id = array_var($_POST, 'id');
        $order_by = array_var($_POST, 'order_by');
        if(!isset($order_by)) $order_by = '';
        tpl_assign('order_by', $order_by);
        $order_by_asc = array_var($_POST, 'order_by_asc');
        if(!isset($order_by_asc)) $order_by_asc = true;
        tpl_assign('order_by_asc', $order_by_asc);
        $report = Reports::getReport($report_id);
        $limit = array_var($_POST, 'exportCSV') || array_var($_POST, 'exportPDF') ? -1 : 50;
        $results = Reports::executeReport($report_id, $params, $order_by, $order_by_asc, 0, $limit, true);
        if(isset($results['columns'])) tpl_assign('columns', $results['columns']);
        if(isset($results['rows'])) tpl_assign('rows', $results['rows']);
        tpl_assign('db_columns', $results['db_columns']);

        if(array_var($_POST, 'exportCSV')){
            $this->generateCSVReport($report, $results);
        }else if(array_var($_POST, 'exportPDF')){
            $this->generatePDFReport($report, $results);
        }else{
            tpl_assign('types', self::get_report_column_types($report_id));
            tpl_assign('template_name', 'view_custom_report');
            tpl_assign('title', $report->getObjectName());
            $ot = ObjectTypes::findById($report->getReportObjectTypeId());
            tpl_assign('model', $ot->getHandlerClass());
            tpl_assign('description', $report->getDescription());
            $conditions = ReportConditions::getAllReportConditions($report_id);
            tpl_assign('conditions', $conditions);
            tpl_assign('parameters', $params);
            tpl_assign('id', $report_id);
            tpl_assign('to_print', true);
            $this->setTemplate('report_printer');
        }
    }

    function generateCSVReport($report, $results){

        $ot = ObjectTypes::findById($report->getReportObjectTypeId());
        Hook::fire("report_header", $ot, $results['columns']);

        $types = self::get_report_column_types($report->getId());
        $filename = str_replace(' ', '_',$report->getObjectName()).date('_YmdHis');
        header('Expires: 0');
        header('Cache-control: private');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-disposition: attachment; filename='.$filename.'.csv');
        foreach($results['columns'] as $col){
            echo $col.';';
        }
        echo "\n";
        foreach($results['rows'] as $row) {
            $i = 0;
            foreach($row as $k => $value){
                if ($k == 'object_type_id') continue;
                $db_col = isset($results['columns'][$i]) && isset($results['db_columns'][$results['columns'][$i]]) ? $results['db_columns'][$results['columns'][$i]] : '';

                $cell = format_value_to_print($db_col, html_to_text($value), ($k == 'link'?'':array_var($types, $k)), array_var($row, 'object_type_id'), '', is_numeric(array_var($results['db_columns'], $k)) ? "Y-m-d" : user_config_option('date_format'));
                $cell = iconv(mb_internal_encoding(),"UTF-8",html_entity_decode($cell ,ENT_COMPAT));
                echo $cell.';';
                $i++;
            }
            echo "\n";
        }
        die();
    }

    function generatePDFReport(Report $report, $results){

        $types = self::get_report_column_types($report->getId());
        $ot = ObjectTypes::findById($report->getReportObjectTypeId());
        eval('$managerInstance = ' . $ot->getHandlerClass() . "::instance();");
        $externalCols = $managerInstance->getExternalColumns();
        $filename = str_replace(' ', '_',$report->getObjectName()).date('_YmdHis');

        $actual_encoding = mb_internal_encoding();

        Hook::fire("report_header", $ot, $results['columns']);

        $pageLayout = $_POST['pdfPageLayout'];
        $fontSize = $_POST['pdfFontSize'];
        include_once(LIBRARY_PATH . '/pdf/fpdf.php');
        $pdf = new FPDF($pageLayout);
        $pdf->setTitle($report->getObjectName());
        $pdf->AddPage();
        $pdf->SetFont('Arial','',$fontSize);
        $pdf->Cell(80);
        if (strtoupper($actual_encoding) == "UTF-8") {
            $report_title = html_entity_decode($report->getObjectName(), ENT_COMPAT);
        } else {
            $report_title = iconv(mb_internal_encoding(), "UTF-8", html_entity_decode($report->getObjectName(), ENT_COMPAT));
        }
        $pdf->Cell(30, 10, $report_title);
        $pdf->Ln(20);
        $colSizes = array();
        $maxValue = array();
        $fixed_col_sizes = array();
        foreach($results['rows'] as $row) {
            $i = 0;
            array_shift ($row);
            foreach($row as $k => $value){
                if(!isset($maxValue[$i])) $maxValue[$i] = '';
                if(strlen(strip_tags($value)) > strlen($maxValue[$i])){
                    $maxValue[$i] = strip_tags($value);
                }
                $i++;
            }
        }
        $k=0;
        foreach ($maxValue as $str) {
            $col_title_len = $pdf->GetStringWidth(array_var($results['columns'], $k));
            $colMaxTextSize = max($pdf->GetStringWidth($str), $col_title_len);
            $db_col = array_var($results['columns'], $k);
            $colType = array_var($types, array_var($results['db_columns'], $db_col, ''), '');
            if($colType == DATA_TYPE_DATETIME && !($report->getObjectTypeName() == 'event' && $results['db_columns'][$db_col] == 'start')){
                $colMaxTextSize = $colMaxTextSize / 2;
                if ($colMaxTextSize < $col_title_len) $colMaxTextSize = $col_title_len;
            }
            $fixed_col_sizes[$k] = $colMaxTextSize;
            $k++;
        }

        $fixed_col_sizes = self::fix_column_widths(($pageLayout=='P'?172:260), $fixed_col_sizes);

        $max_char_len = array();
        $i = 0;
        foreach($results['columns'] as $col){
            $colMaxTextSize = $fixed_col_sizes[$i];
            $colFontSize = $colMaxTextSize + 5;
            $colSizes[$i] = $colFontSize;

            if (strtoupper($actual_encoding) == "UTF-8") {
                $col_name = html_entity_decode($col, ENT_COMPAT);
            } else {
                $col_name = iconv(mb_internal_encoding(), "UTF-8", html_entity_decode($col, ENT_COMPAT));
            }
            $pdf->Cell($colFontSize, 7, $col_name);
            $max_char_len[$i] = self::get_max_length_from_pdfsize($pdf, $colFontSize);
            $i++;
        }

        $lastColX = $pdf->GetX();
        $pdf->Ln();
        $pdf->Line($pdf->GetX(), $pdf->GetY(), $lastColX, $pdf->GetY());
        foreach($results['rows'] as $row) {
            $i = 0;
            $more_lines = array();
            $col_offsets = array();
            foreach($row as $k => $value){
                if ($k == 'object_type_id') continue;
                $db_col = isset($results['columns'][$i]) && isset($results['db_columns'][$results['columns'][$i]]) ? $results['db_columns'][$results['columns'][$i]] : '';

                $cell = format_value_to_print($db_col, html_to_text($value), ($k == 'link'?'':array_var($types, $k)), array_var($row, 'object_type_id'), '', is_numeric(array_var($results['db_columns'], $k)) ? "Y-m-d" : user_config_option('date_format'));

                if (strtoupper($actual_encoding) == "UTF-8") {
                    $cell = html_entity_decode($cell, ENT_COMPAT);
                } else {
                    $cell = iconv(mb_internal_encoding(), "UTF-8", html_entity_decode($cell, ENT_COMPAT));
                }

                $splitted = self::split_column_value($cell, $max_char_len[$i]);
                $cell = $splitted[0];
                if (count($splitted) > 1) {
                    array_shift($splitted);
                    $ml = 0;
                    foreach ($splitted as $sp_val) {
                        if (!isset($more_lines[$ml]) || !is_array($more_lines[$ml])) $more_lines[$ml] = array();
                        $more_lines[$ml][$i] = $sp_val;
                        $ml++;
                    }
                    $col_offsets[$i] = $pdf->x;
                }

                $pdf->Cell($colSizes[$i],7,$cell);
                $i++;
            }
            foreach ($more_lines as $ml_values) {
                $pdf->Ln();
                foreach ($ml_values as $col_idx => $col_val) {
                    $pdf->SetX($col_offsets[$col_idx]);
                    $pdf->Cell($colSizes[$col_idx],7,$col_val);
                }
            }
            $pdf->Ln();
            $pdf->SetDrawColor(220, 220, 220);
            $pdf->Line($pdf->GetX(), $pdf->GetY(), $lastColX, $pdf->GetY());
            $pdf->SetDrawColor(0, 0, 0);
        }
        $filename = ROOT."/tmp/".gen_id().".pdf";
        $pdf->Output($filename, "F");
        download_file($filename, "application/pdf", $report->getObjectName(), true);
        unlink($filename);
        die();
    }

    private function get_report_column_types($report_id) {
        $col_types = array();
        $report = Reports::getReport($report_id);
        $ot = ObjectTypes::findById($report->getReportObjectTypeId());
        $model = $ot->getHandlerClass();
        $manager = new $model();

        $columns = ReportColumns::getAllReportColumns($report_id);

        foreach ($columns as $col) {
            $cp_id = $col->getCustomPropertyId();
            if ($cp_id == 0) {
                $col_types[$col->getFieldName()] = $manager->getColumnType($col->getFieldName());
            } else {
                $cp = CustomProperties::getCustomProperty($cp_id);
                if ($cp) {
                    $col_types[$cp->getName()] = $cp->getOgType();
                }
            }
        }

        return $col_types;
    }
}