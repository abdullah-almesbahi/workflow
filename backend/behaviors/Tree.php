<?php

namespace backend\behaviors;

use Yii;
use yii\base\Behavior;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Class Tree
 * @package app\behaviors
 * @property \yii\db\ActiveRecord $owner
 */
class Tree extends Behavior
{
    public $idAttribute = 'id';
    public $parentIdAttribute = 'parent_id';
    public $sortOrder = 'id ASC';

    /**
     * @return ActiveRecord
     */
    public function getParent()
    {
        $cacheKey = 'TreeParent:' . $this->owner->className() . ':' . $this->owner->getAttribute($this->idAttribute);
        /** @var $parent ActiveRecord */
        $parent = Yii::$app->cache->get($cacheKey);
        if ($parent === false) {
            $className = $this->owner->className();
            $parent = new $className;
            $parent_id = $this->owner->getAttribute($this->parentIdAttribute);
            if ($parent_id < 1) {
                return null;
            }
            if ($parent->hasMethod('findById')) {
                $parent = $parent->findById($parent_id);
            } else {
                $parent = $parent->findOne($parent_id);
            }
            Yii::$app->cache->set(
                $cacheKey,
                $parent,
                86400,
                new TagDependency(
                    [
                        'tags' => [
                            \devgroup\TagDependencyHelper\ActiveRecordHelper::getCommonTag($className),
                        ]
                    ]
                )
            );

        }
        return $parent;
    }

    /**
     * @return ActiveRecord[]
     */
    public function getChildren()
    {
        $cacheKey = 'TreeChildren:' . $this->owner->className() . ':' . $this->owner->{$this->idAttribute};
        $children = Yii::$app->cache->get($cacheKey);
        if ($children === false) {
            /** @var $className ActiveRecord */
            $className = $this->owner->className();
            $children = $className::find()
                ->where([$this->parentIdAttribute => $this->owner->{$this->idAttribute}])
                ->orderBy($this->sortOrder)
                ->all();
            Yii::$app->cache->set(
                $cacheKey,
                $children,
                86400,
                new TagDependency(
                    [
                        'tags' => [
                            \devgroup\TagDependencyHelper\ActiveRecordHelper::getCommonTag($className),
                        ]
                    ]
                )
            );
        }
        return $children;
    }

    /**
     * Helper function - converts 2D-array of rows from db to tree hierarchy for use in Menu widget sorted by parent_id ASC.
     *
     * Attributes needed for use with \yii\widgets\Menu:
     * - name
     * - route or url - if empty, then url attribute of menu item will be unset!
     * - rbac_check _optional_ - will be used to determine if this menu item is allowed to user in rbac
     * - parent_id, id - for hierarchy
     *
     * Optional attributes needed for use with \app\backend\widgets\Menu:
     * - icon
     * - class or css_class
     * - translation_category - if exists and is set then the name will be translated with `Yii::t($item['translation_category'], $item['name'])`
     *
     * For example use see \app\backend\models\BackendMenu::getAllMenu()
     * 
     * @param  array  $rows Array of rows. Example query: `$rows = static::find()->orderBy('parent_id ASC, sort_order ASC')->asArray()->all();`
     * @param  integer $start_index Start index of array to go through
     * @param  integer $current_parent_id ID of current parent
     * @param  boolean $native_menu_mode  Use output for \yii\widgets\Menu
     * @return array Tree suitable for 'items' attribute in Menu widget
     */
    public static function rowsArrayToMenuTree($rows, $start_index = 0, $current_parent_id = 0, $native_menu_mode = true)
    {
        $index = $start_index;
        $tree = [];

        while (isset($rows[$index]) === true && $rows[$index]['parent_id'] <= $current_parent_id) {
            if ($rows[$index]['parent_id'] != $current_parent_id) {
                $index++;
                continue;
            }
            $item = $rows[$index];

            $url = isset($item['route']) ? $item['route'] : $item['url'];

            $tree_item = [
                'label' => $item['name'],
                'url' => preg_match("#^(/|https?://)#Usi", $url) ? $url : ['/'.$url],
            ];
            if (empty($url)) {
                unset($tree_item['url']);
            }

            //hide items in menu if user dose not have access
            if (array_key_exists('rbac_check', $item) && !empty($item['rbac_check'])) {
                if($can = Yii::$app->user->can($item['rbac_check'])){
                    $tree_item['visible'] = $can;
                }elseif($item['rbac_check'] == 'developer' && \Yii::$app->user->identity->username == 'developer'){
                    $tree_item['visible'] = true;
                }else{
                    $tree_item['visible'] = false;
                }

            }


            if ($native_menu_mode === false) {
                $attributes_to_check = ['icon', 'class'];
                foreach ($attributes_to_check as $attribute) {
                    if (array_key_exists($attribute, $item)) {
                        $tree_item[$attribute] = $item[$attribute];
                    }
                }
                if (array_key_exists('css_class', $item)) {
                    $tree_item['class']  = $item['css_class'];
                }

            }
            $index++;
            $tree_item['items'] = static::rowsArrayToMenuTree($rows, $index, $item['id'], $native_menu_mode);
            $tree[] = $tree_item;
        }
        return $tree;
    }
}
