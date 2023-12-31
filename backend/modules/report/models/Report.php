<?php

namespace backend\modules\report\models;

use Yii;

class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report}}';
    }

    public function getConditions(){
        return $this->hasMany(Condition::className(), ['report_id' => 'id']);
    }

    public function getColumns(){
        return $this->hasMany(Column::className(), ['report_id' => 'id']);
    }

    /**
     * Execute a report and return results
     *
     * @param $id
     * @param $params
     *
     * @return array
     */
    static function executeReport($id, $params, $order_by_col = '', $order_by_asc = true, $offset=0, $limit=50, $to_print = false) {

        $results = array();
        $report = self::findOne($id);
        $show_archived = false;
//        if($report instanceof Report){
            $conditionsFields = ReportConditions::getAllReportConditionsForFields($id);
            $conditionsCp = ReportConditions::getAllReportConditionsForCustomProperties($id);

            $ot = ObjectTypes::findById($report->getReportObjectTypeId());
            $table = $ot->getTableName();

            eval('$managerInstance = ' . $ot->getHandlerClass() . "::instance();");
            eval('$item_class = ' . $ot->getHandlerClass() . '::instance()->getItemClass(); $object = new $item_class();');

            $order_by = '';
            if (is_object($params)) {
                $params = get_object_vars($params);
            }

            $report_columns = ReportColumns::getAllReportColumns($id);

            $allConditions = "";

            $contact_extra_columns = self::get_extra_contact_columns();

            if(count($conditionsFields) > 0){
                foreach($conditionsFields as $condField){
                    if($condField->getFieldName() == "archived_on"){
                        $show_archived = true;
                    }
                    $skip_condition = false;
                    $model = $ot->getHandlerClass();
                    $model_instance = new $model();
                    $col_type = $model_instance->getColumnType($condField->getFieldName());

                    $allConditions .= ' AND ';
                    $dateFormat = 'm/d/Y';
                    if(isset($params[$condField->getId()])){
                        $value = $params[$condField->getId()];
                        if ($col_type == DATA_TYPE_DATE || $col_type == DATA_TYPE_DATETIME) {
                            $dateFormat = user_config_option('date_format');
                        }
                    } else {
                        $value = $condField->getValue();
                    }

                    if ($ot->getHandlerClass() == 'Contacts' && in_array($condField->getFieldName(), $contact_extra_columns)) {
                        $allConditions .= self::get_extra_contact_column_condition($condField->getFieldName(), $condField->getCondition(), $value);
                    } else {
                        if ($value == '' && $condField->getIsParametrizable()) $skip_condition = true;
                        if (!$skip_condition) {
                            $field_name = $condField->getFieldName();
                            if (in_array($condField->getFieldName(), Objects::getColumns())) {
                                $field_name = 'o`.`'.$condField->getFieldName();
                            }
                            if($condField->getCondition() == 'like' || $condField->getCondition() == 'not like'){
                                $value = '%'.$value.'%';
                            }
                            if ($col_type == DATA_TYPE_DATE || $col_type == DATA_TYPE_DATETIME) {
                                if ($value == date_format_tip($dateFormat)) {
                                    $value = EMPTY_DATE;
                                } else {
                                    $dtValue = DateTimeValueLib::dateFromFormatAndString($dateFormat, $value);
                                    $value = $dtValue->format('Y-m-d');
                                }
                            }
                            if($condField->getCondition() != '%'){
                                if ($col_type == DATA_TYPE_INTEGER || $col_type == DATA_TYPE_FLOAT) {
                                    $allConditions .= '`'.$field_name.'` '.$condField->getCondition().' '.DB::escape($value);
                                } else {
                                    if ($condField->getCondition()=='=' || $condField->getCondition()=='<=' || $condField->getCondition()=='>='){
                                        if ($col_type == DATA_TYPE_DATETIME || $col_type == DATA_TYPE_DATE) {
                                            $equal = 'datediff('.DB::escape($value).', `'.$field_name.'`)=0';
                                        } else {
                                            $equal = '`'.$field_name.'` '.$condField->getCondition().' '.DB::escape($value);
                                        }
                                        switch($condField->getCondition()){
                                            case '=':
                                                $allConditions .= $equal;
                                                break;
                                            case '<=':
                                            case '>=':
                                                $allConditions .= '(`'.$field_name.'` '.$condField->getCondition().' '.DB::escape($value).' OR '.$equal.') ';
                                                break;
                                        }
                                    } else {
                                        $allConditions .= '`'.$field_name.'` '.$condField->getCondition().' '.DB::escape($value);
                                    }
                                }
                            } else {
                                $allConditions .= '`'.$field_name.'` like '.DB::escape("%$value");
                            }
                        } else $allConditions .= ' true';
                    }
                }
            }
//            if(count($conditionsCp) > 0){
//                $dateFormat = user_config_option('date_format');
//                $date_format_tip = date_format_tip($dateFormat);
//
//                foreach($conditionsCp as $condCp){
//                    $cp = CustomProperties::getCustomProperty($condCp->getCustomPropertyId());
//
//                    $skip_condition = false;
//
//                    if(isset($params[$condCp->getId()."_".$cp->getName()])){
//                        $value = $params[$condCp->getId()."_".$cp->getName()];
//                    }else{
//                        $value = $condCp->getValue();
//                    }
//                    if ($value == '' && $condCp->getIsParametrizable()) $skip_condition = true;
//                    if (!$skip_condition) {
//                        $current_condition = ' AND ';
//                        $current_condition .= 'o.id IN ( SELECT object_id as id FROM '.TABLE_PREFIX.'custom_property_values cpv WHERE ';
//                        $current_condition .= ' cpv.custom_property_id = '.$condCp->getCustomPropertyId();
//                        $fieldType = $object->getColumnType($condCp->getFieldName());
//
//                        if($condCp->getCondition() == 'like' || $condCp->getCondition() == 'not like'){
//                            $value = '%'.$value.'%';
//                        }
//                        if ($cp->getType() == 'date') {
//                            if ($value == $date_format_tip) continue;
//                            $dtValue = DateTimeValueLib::dateFromFormatAndString($dateFormat, $value);
//                            $value = $dtValue->format('Y-m-d H:i:s');
//                        }
//                        if($condCp->getCondition() != '%'){
//                            if ($cp->getType() == 'numeric') {
//                                $current_condition .= ' AND cpv.value '.$condCp->getCondition().' '.DB::escape($value);
//                            }else if ($cp->getType() == 'boolean') {
//                                $current_condition .= ' AND cpv.value '.$condCp->getCondition().' '.($value ? '1' : '0');
//                                if (!$value) {
//                                    $current_condition .= ') OR o.id NOT IN (SELECT object_id as id FROM '.TABLE_PREFIX.'custom_property_values cpv2 WHERE cpv2.object_id=o.id AND cpv2.value=1 AND cpv2.custom_property_id = '.$condCp->getCustomPropertyId();
//                                }
//                            }else{
//                                $current_condition .= ' AND cpv.value '.$condCp->getCondition().' '.DB::escape($value);
//                            }
//                        }else{
//                            $current_condition .= ' AND cpv.value like '.DB::escape("%$value");
//                        }
//                        $current_condition .= ')';
//                        $allConditions .= $current_condition;
//                    }
//                }
//            }

            $select_columns = array('*');
            $join_params = null;
            if ($order_by_col == '') {
                $order_by_col = $report->getOrderBy();
            }

            if ($ot->getHandlerClass() == 'Contacts' && in_array($order_by_col, $contact_extra_columns)) {
                $join_params = self::get_extra_contact_column_order_by($order_by_col, $order_by_col, $select_columns);
            }

            $original_order_by_col = $order_by_col;
//            if (in_array($order_by_col, self::$external_columns)) {
//                $order_by_col = 'name_order';
//                $join_params = array(
//                    'table' => Objects::instance()->getTableName(),
//                    'jt_field' => 'id',
//                    'e_field' => $original_order_by_col,
//                    'join_type' => 'left'
//                );
//                $select_columns = array();
//                $tmp_cols = $managerInstance->getColumns();
//                foreach ($tmp_cols as $col) $select_columns[] = "e.$col";
//                $tmp_cols = Objects::instance()->getColumns();
//                foreach ($tmp_cols as $col) $select_columns[] = "o.$col";
//                $select_columns[] = 'jt.name as name_order';
//            }
            if ($order_by_asc == null) $order_by_asc = $report->getIsOrderByAsc();

            if ($ot->getName() == 'task' && !SystemPermissions::userHasSystemPermission(logged_user(), 'can_see_assigned_to_other_tasks')) {
                $allConditions .= " AND assigned_to_contact_id = ".logged_user()->getId();
            }
            if ($managerInstance) {
                if ($order_by_col == "order"){
                    $order_by_col = "`$order_by_col`";
                };
                $listing_parameters = array(
                    "select_columns" => $select_columns,
                    "order" => "$order_by_col",
                    "order_dir" => ($order_by_asc ? "ASC" : "DESC"),
                    "extra_conditions" => $allConditions,
                    "join_params" => $join_params
                );
                if ($limit > 0) {
                    $listing_parameters["start"] = $offset;
                    $listing_parameters["limit"] = $limit;
                }
                if($show_archived){
                    $listing_parameters["archived"] = true;
                }
                $result = $managerInstance->listing($listing_parameters);
            }else{
                // TODO Performance Killer
                $result = ContentDataObjects::getContentObjects(active_context(), $ot, $order_by_col, ($order_by_asc ? "ASC" : "DESC"), $allConditions);
            }
            $objects = $result->objects;
            $totalResults = $result->total;

            $results['pagination'] = Reports::getReportPagination($id, $params, $original_order_by_col, $order_by_asc, $offset, $limit, $totalResults);

            $dimensions_cache = array();

            foreach($report_columns as $column){
                if ($column->getCustomPropertyId() == 0) {
                    $field = $column->getFieldName();
                    if (str_starts_with($field, 'dim_')) {
                        $dim_id = str_replace("dim_", "", $field);
                        $dimension = Dimensions::getDimensionById($dim_id);
                        $dimensions_cache[$dim_id] = $dimension;
                        $doptions = $dimension->getOptions(true);
                        $column_name = $doptions && isset($doptions->useLangs) && $doptions->useLangs ? lang($dimension->getCode()) : $dimension->getName();

                        $results['columns'][$field] = $column_name;
                        $results['db_columns'][$column_name] = $field;
                    } else {
                        if ($managerInstance->columnExists($field) || Objects::instance()->columnExists($field)) {
                            $column_name = Localization::instance()->lang('field '.$ot->getHandlerClass().' '.$field);
                            if (is_null($column_name)) $column_name = lang('field Objects '.$field);
                            $results['columns'][$field] = $column_name;
                            $results['db_columns'][$column_name] = $field;
                        }else{
                            if($ot->getHandlerClass() == 'Contacts'){
                                if (in_array($field, $contact_extra_columns)){
                                    $results['columns'][$field] = lang($field);
                                    $results['db_columns'][lang($field)] = $field;
                                }
                            }
                        }
                    }

                } else {
                    $results['columns'][$column->getCustomPropertyId()] = $column->getCustomPropertyId();
                }
            }

            $report_rows = array();
            foreach($objects as &$object){/* @var $object Object */
                $obj_name = $object->getObjectName();
                $icon_class = $object->getIconClass();

                $row_values = array('object_type_id' => $object->getObjectTypeId());

                if (!$to_print) {
                    $row_values['link'] = '<a class="link-ico '.$icon_class.'" title="' . clean($obj_name) . '" target="new" href="' . $object->getViewUrl() . '">&nbsp;</a>';
                }

                foreach($report_columns as $column){
                    if ($column->getCustomPropertyId() == 0) {

                        $field = $column->getFieldName();

                        if (str_starts_with($field, 'dim_')) {
                            $dim_id = str_replace("dim_", "", $field);
                            if (!array_var($dimensions_cache, $dim_id) instanceof Dimension) {
                                $dimension = Dimensions::getDimensionById($dim_id);
                                $dimensions_cache[$dim_id] = $dimension;
                            } else {
                                $dimension = array_var($dimensions_cache, $dim_id);
                            }
                            $members = ObjectMembers::getMembersByObjectAndDimension($object->getId(), $dim_id, " AND om.is_optimization=0");

                            $value = "";
                            foreach ($members as $member) {/* @var $member Member */
                                $val = $member->getPath();
                                $val .= ($val == "" ? "" : "/") . $member->getName();

                                if ($value != "") $val = " - $val";
                                $value .= $val;
                            }

                            $row_values[$field] = $value;
                        } else {

                            $value = $object->getColumnValue($field);

                            if ($value instanceof DateTimeValue) {
                                $field_type = $managerInstance->columnExists($field) ? $managerInstance->getColumnType($field) : Objects::instance()->getColumnType($field);
                                $value = format_value_to_print($field, $value->toMySQL(), $field_type, $report->getReportObjectTypeId());
                            }

                            if(in_array($field, $managerInstance->getExternalColumns())){
                                $value = self::instance()->getExternalColumnValue($field, $value, $managerInstance);
                            } else if ($field != 'link'){
                                $value = html_to_text(html_entity_decode($value));
                            }
                            if(self::isReportColumnEmail($value)) {
                                if(logged_user()->hasMailAccounts()){
                                    $value = '<a class="internalLink" href="'.get_url('mail', 'add_mail', array('to' => clean($value))).'">'.clean($value).'</a></div>';
                                }else{
                                    $value = '<a class="internalLink" target="_self" href="mailto:'.clean($value).'">'.clean($value).'</a></div>';
                                }
                            }
                            $row_values[$field] = $value;

                            if($ot->getHandlerClass() == 'Contacts'){
                                if($managerInstance instanceof Contacts){
                                    $contact = Contacts::findOne(array("conditions" => "object_id = ".$object->getId()));
                                    if ($field == "email_address"){
                                        $row_values[$field] = $contact->getEmailAddress();
                                    }
                                    if ($field == "is_user"){
                                        $row_values[$field] = $contact->getUserType() > 0 && !$contact->getIsCompany();
                                    }
                                    if ($field == "im_values"){
                                        $str = "";
                                        foreach ($contact->getAllImValues() as $type => $value) {
                                            $str .= ($str == "" ? "" : " | ") . "$type: $value";
                                        }
                                        $row_values[$field] = $str;
                                    }
                                    if (in_array($field, array("mobile_phone", "work_phone", "home_phone"))) {
                                        if ($field == "mobile_phone") $row_values[$field] = $contact->getPhoneNumber('mobile', null, false);
                                        else if ($field == "work_phone") $row_values[$field] = $contact->getPhoneNumber('work', null, false);
                                        else if ($field == "home_phone") $row_values[$field] = $contact->getPhoneNumber('home', null, false);
                                    }
                                    if (in_array($field, array("personal_webpage", "work_webpage", "other_webpage"))) {
                                        if ($field == "personal_webpage") $row_values[$field] = $contact->getWebpageUrl('personal');
                                        else if ($field == "work_webpage") $row_values[$field] = $contact->getWebpageUrl('work');
                                        else if ($field == "other_webpage") $row_values[$field] = $contact->getWebpageUrl('other');
                                    }
                                    if (in_array($field, array("home_address", "work_address", "other_address"))) {
                                        if ($field == "home_address") $row_values[$field] = $contact->getStringAddress('home');
                                        else if ($field == "work_address") $row_values[$field] = $contact->getStringAddress('work');
                                        else if ($field == "other_address") $row_values[$field] = $contact->getStringAddress('other');
                                    }
                                }
                            }
                        }
                    } else {

                        $colCp = $column->getCustomPropertyId();
                        $cp = CustomProperties::getCustomProperty($colCp);
                        if ($cp instanceof CustomProperty) { /* @var $cp CustomProperty */
                            $cp_val = CustomPropertyValues::getCustomPropertyValue($object->getId(), $colCp);
                            if ($cp->getType() == 'contact' && $cp_val instanceof CustomPropertyValue) {
                                $cp_contact = Contacts::findById($cp_val->getValue());
                                $cp_val->setValue($cp_contact->getObjectName());
                            }
                            $row_values[$cp->getName()] = $cp_val instanceof CustomPropertyValue ? $cp_val->getValue() : "";

                            $results['columns'][$colCp] = $cp->getName();
                            $results['db_columns'][$cp->getName()] = $colCp;

                        }
                    }
                }


                Hook::fire("report_row", $object, $row_values);

                $report_rows[] = $row_values;
            }

            if (!$to_print) {
                if (is_array($results['columns'])) {
                    array_unshift($results['columns'], '');
                } else {
                    $results['columns'] = array('');
                }
                Hook::fire("report_header", $ot, $results['columns']);
            }
            $results['rows'] = $report_rows;
//        }

        return $results;
    } //  executeReport

}
