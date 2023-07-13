<?php

use yii\db\Schema;
use yii\db\Migration;

class m150211_181435_create_setting extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%settings}}',
            [
                'id' => Schema::TYPE_PK,
                'type' => Schema::TYPE_STRING,
                'section' => Schema::TYPE_STRING,
                'key' => Schema::TYPE_STRING,
                'value' => Schema::TYPE_TEXT,
                'active' => Schema::TYPE_BOOLEAN,
                'created' => Schema::TYPE_DATETIME,
                'modified' => Schema::TYPE_DATETIME,
            ]
        );
    }

    public function down()
    {
        echo "m150211_181435_create_setting cannot be reverted.\n";

        return false;
    }
}
