<?php

use yii\db\Schema;
use yii\db\Migration;

class m150210_201426_create_action_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%actionlog}}', [
            'id' => Schema::TYPE_BIGPK,
            'user_id' => Schema::TYPE_BIGINT . ' NOT NULL DEFAULT 0',
            'user_remote' => Schema::TYPE_STRING . ' NOT NULL',
            'time' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'action' => Schema::TYPE_STRING . ' NOT NULL',
            'category' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_STRING . ' NULL',
            'message' => Schema::TYPE_TEXT . ' NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%actionlog}}');

        return false;
    }
}
