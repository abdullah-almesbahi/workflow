<?php

namespace backend\models;

use app\data\models\Export;
use app\data\models\Import;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "object".
 *
 * @property integer $id
 * @property string $name
 * @property string $object_class
 * @property string $object_table_name
 * @property string $column_properties_table_name
 * @property string $eav_table_name
 * @property string $categories_table_name
 * @property string $link_slug_category
 * @property string $link_slug_static_value
 */
class Object extends ActiveRecord
{
    private static $identity_map = [];
    private static $ids_for_class_name = [];
    private static $select_array_cache = null;

    public function behaviors()
    {
        return [
            [
                'class' => \devgroup\TagDependencyHelper\ActiveRecordHelper::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%object}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['name', 'object_class', 'object_table_name', 'column_properties_table_name',
                    'eav_table_name', 'categories_table_name', 'link_slug_category', 'link_slug_static_value'],
                'required'
            ],
            [
                ['name', 'object_class', 'object_table_name', 'column_properties_table_name',
                    'eav_table_name', 'categories_table_name', 'link_slug_category', 'link_slug_static_value'],
                'string'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'object_class' => Yii::t('app', 'Object Class'),
            'object_table_name' => Yii::t('app', 'Object Table Name'),
            'column_properties_table_name' => Yii::t('app', 'Column Properties Table Name'),
            'eav_table_name' => Yii::t('app', 'EAV Table Name'),
            'categories_table_name' => Yii::t('app', 'Categories Table Name'),
            'link_slug_category' => Yii::t('app', 'Link Slug Category'),
            'link_slug_static_value' => Yii::t('app', 'Link Slug Static Value'),
        ];
    }

    /**
     * Возвращает модель по ID с использованием IdentityMap
     * @param integer $id
     * @return Object
     */
    public static function findById($id)
    {
        if (!isset(static::$identity_map[$id])) {
            static::$identity_map[$id] = Yii::$app->cache->get('Object: ' . $id);
            if (static::$identity_map[$id] === false) {
                static::$identity_map[$id] = Object::findOne($id);
                if (is_object(static::$identity_map[$id])) {
                    static::$ids_for_class_name[
                        static::$identity_map[$id]->object_class
                    ] = $id;
                    Yii::$app->cache->set(
                        'Object: ' . $id,
                        static::$identity_map[$id],
                        86400,
                        new TagDependency(
                            [
                                'tags' => [
                                    \devgroup\TagDependencyHelper\ActiveRecordHelper::getObjectTag(static::className(), $id),
                                ],
                            ]
                        )
                    );
                }
            }
        }
        return static::$identity_map[$id];
    }

    /**
     * @param string $class_name
     * @return null|Object
     */
    public static function getForClass($class_name)
    {
        if (isset(static::$ids_for_class_name[$class_name])) {
            $id = static::$ids_for_class_name[$class_name];
            return Object::findById($id);
        } else {
            $object = Yii::$app->cache->get('ObjectByClassName: ' . $class_name);
            if ($object === false) {
                $object = Object::find()
                    ->where(
                        [
                            'object_class' => $class_name,
                        ]
                    )->one();
                if ($object !== null) {
                    Yii::$app->cache->set(
                        'ObjectByClassName: ' . $class_name,
                        $object,
                        86400,
                        new TagDependency(
                            [
                                'tags' => [
                                    \devgroup\TagDependencyHelper\ActiveRecordHelper::getObjectTag($object, $object->id),
                                ],
                            ]
                        )
                    );
                }
            }
            if (is_object($object)) {
                static::$identity_map[$object->id] = $object;
                static::$ids_for_class_name[$class_name] = $object->id;
                return static::$identity_map[$object->id];
            }
        }
        return null;
    }

    /**
     * Возвращает список всех объектов
     * Ключ - ID
     * Значение - name
     * Используется для фильтрации в таблицах и выборе объекта в форме
     */
    public static function getSelectArray()
    {
        if (static::$select_array_cache === null) {
            static::$select_array_cache = Yii::$app->cache->get('ObjectsList');
            if (static::$select_array_cache === false) {
                $rows = (new Query())
                    ->select('id, name')
                    ->from(Object::tableName())
                    ->all();
                static::$select_array_cache = ArrayHelper::map($rows, 'id', 'name');
            }
            Yii::$app->cache->set(
                'ObjectsList',
                static::$select_array_cache,
                86400,
                new TagDependency(
                    [
                        'tags' => [
                            \devgroup\TagDependencyHelper\ActiveRecordHelper::getCommonTag(static::className()),
                        ],
                    ]
                )
            );
        }
        return static::$select_array_cache;
    }

    public function getLastExport()
    {
        return $this->hasOne(Export::className(), ['object_id' => 'id'])
            ->andOnCondition(
                [
                    Export::tableName() . '.user_id' => Yii::$app->user->id,
                ]
            );
    }

    public function getLastImport()
    {
        return $this->hasOne(Import::className(), ['object_id' => 'id'])
            ->andOnCondition(
                [
                    Import::tableName() . '.user_id' => Yii::$app->user->id,
                ]
            );
    }
}
