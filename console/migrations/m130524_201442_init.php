<?php

use yii\db\Migration;
use common\models\User;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        mb_internal_encoding("UTF-8");
        // Schemes
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%auth_rule}}',
            [
                'name' => 'VARCHAR(64) NOT NULL PRIMARY KEY',
                'data' => 'TEXT',
                'created_at' => 'INT DEFAULT NULL',
                'updated_at' => 'INT DEFAULT NULL',
            ],
            $tableOptions
        );
        $this->createTable(
            '{{%auth_item}}',
            [
                'name' => 'VARCHAR(64) NOT NULL PRIMARY KEY',
                'type' => 'INT NOT NULL',
                'description' => 'TEXT',
                'rule_name' => 'VARCHAR(64)',
                'data' => 'TEXT',
                'created_at' => 'INT DEFAULT NULL',
                'updated_at' => 'INT DEFAULT NULL',
                'KEY `rule_name` (`rule_name`)',
                'KEY `type` (`type`)',
                'CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`)
                    REFERENCES {{%auth_rule}} (`name`) ON DELETE SET NULL ON UPDATE CASCADE'
            ],
            $tableOptions
        );
        $this->createTable(
            '{{%auth_item_child}}',
            [
                'parent' => 'VARCHAR(64) NOT NULL',
                'child' => 'VARCHAR(64) NOT NULL',
                'PRIMARY KEY (`parent`, `child`)',
                'KEY `child` (`child`)',
                'CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`)
                    REFERENCES {{%auth_item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
                'CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`)
                    REFERENCES {{%auth_item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            $tableOptions
        );
        $this->createTable(
            '{{%auth_assignment}}',
            [
                'item_name' => 'VARCHAR(64) NOT NULL',
                'user_id' => 'VARCHAR(64) NOT NULL',
                'created_at' => 'INT DEFAULT NULL',
                'updated_at' => 'INT DEFAULT NULL',
                'rule_name' => 'VARCHAR(64) DEFAULT NULL',
                'data' => 'TEXT',
                'PRIMARY KEY (`item_name`, `user_id`)',
                'KEY `rule_name` (`rule_name`)',
                'CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`)
                    REFERENCES {{%auth_item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
                'CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`rule_name`)
                    REFERENCES {{%auth_rule}} (`name`) ON DELETE SET NULL ON UPDATE CASCADE',
            ],
            $tableOptions
        );

        $this->createTable(
            '{{%error_log}}',
            [
                'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'url_id' => 'INT UNSIGNED NOT NULL',
                'http_code' => 'SMALLINT',
                'timestamp' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'info' => 'TEXT DEFAULT NULL',
                'server_vars' => 'TEXT DEFAULT NULL',
                'request_vars' => 'TEXT DEFAULT NULL',
            ],
            $tableOptions
        );

        $this->createTable(
            '{{%notification}}',
            [
                'id' => 'BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'user_id' => 'INTEGER UNSIGNED NOT NULL',
                'date' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'type' => 'ENUM(\'default\', \'primary\', \'success\', \'info\', \'warning\', \'danger\') DEFAULT \'default\'',
                'label' => 'VARCHAR(255) NOT NULL',
                'message' => 'TEXT NOT NULL',
                'viewed' => 'TINYINT UNSIGNED DEFAULT \'0\'',
                'KEY `user_id` (`user_id`)',
            ],
            $tableOptions
        );

        $this->createTable('{{%user}}', [
            'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'username' => 'VARCHAR(255) NOT NULL',
            'auth_key' => 'varbinary(32) NOT NULL',
            'password_hash' => 'VARCHAR(255) NOT NULL',
            'password_reset_token' => 'varbinary(32)',
            'email' => 'VARCHAR(255) NOT NULL',
            'status' => 'TINYINT UNSIGNED DEFAULT 10',
            'create_time' => 'INT NOT NULL',
            'update_time' => 'INT NOT NULL',
            'first_name' => 'VARCHAR(255)',
            'last_name' => 'VARCHAR(255)',
            'UNIQUE KEY `uq-user-username` (`username`)',
        ], $tableOptions);

        $this->createTable(
            '{{%user_service}}',
            [
                'id' => 'INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'user_id' => 'INT UNSIGNED NOT NULL',
                'service_type' => 'VARCHAR(255) NOT NULL',
                'service_id' => 'VARCHAR(255) NOT NULL',
                'KEY `ix-user_service-user_id` (`user_id`)',
                'UNIQUE KEY `uq-user-service-service_type-service_id` (`service_type`, `service_id`)',
            ],
            $tableOptions
        );

        $username = $email = $password = null;

        if (getenv("ADMIN_USERNAME")) {
            echo "INFO: Using admin user details provided by ENV variables...\n";
            $username = getenv("ADMIN_USERNAME");
            $email = getenv("ADMIN_EMAIL");
            $password = getenv("ADMIN_PASSWORD");

        } else {
            $stdIn = fopen("php://stdin", "r");
            do {
                echo 'Enter admin username (3 or more chars): ';
                $username = trim(fgets($stdIn));
            } while (mb_strlen($username) < 3);
            do {
                echo 'Enter admin email: ';
                $email = trim(fgets($stdIn));
            } while (preg_match('#^\w[\w\d\.\-_]*@[\w\d\.\-_]+\.\w{2,6}$#i', $email) != 1);
            do {
                do {
                    echo 'Enter admin password (8 or more chars): ';
                    $password = trim(fgets($stdIn));
                } while (mb_strlen($password) < 8);
                do {
                    echo 'Confirm admin password: ';
                    $confirmPassword = trim(fgets($stdIn));
                } while (mb_strlen($confirmPassword) < 8);
                if ($password != $confirmPassword) {
                    echo "Password does not match the confirm password\n";
                }
            } while ($password != $confirmPassword);
            fclose($stdIn);
        }

        $user = new User(['scenario' => 'signup']);
        $user->username = $username;
        $user->password = $password;
        $user->email = $email;
        $user->save(false);
        $this->batchInsert(
            '{{%auth_item}}',
            ['name', 'type', 'description'],
            [
                ['admin', '1', 'Administrator'],
                ['manager', '1', 'Manager'],
                ['administrate', '2', 'Administrate panel'],
                ['api manage', '2', 'API management'],
                //['seo manage', '2', 'SEO management'],
                ['user manage', '2', 'User management'],
                ['cache manage', '2', 'Cache management'],
//                ['content manage', '2', 'Content management'],
//                ['shop manage', '2', 'Shop management'],
//                ['order manage', '2', 'Order management'],
//                ['category manage', '2', 'Category management'],
//                ['product manage', '2', 'Product management'],
//                ['property manage', '2', 'Property management'],
                ['view manage', '2', 'View management'],
//                ['review manage', '2', 'Review management'],
                ['navigation manage', '2', 'Navigation management'],
//                ['form manage', '2', 'Form management'],
//                ['media manage', '2', 'Media management'],
//                ['order status manage', '2', 'Order status management'],
//                ['payment manage', '2', 'Payment type management'],
//                ['shipping manage', '2', 'Shipping option management'],
//                ['newsletter manage', '2', 'Newsletter management'],
//                ['monitoring manage', '2', 'Monitoring management'],
//                ['data manage', '2', 'Data management'],
                ['setting manage', '2', 'Setting management'],
            ]
        );
        $this->batchInsert(
            '{{%auth_item_child}}',
            ['parent', 'child'],
            [
//                ['shop manage', 'category manage'],
//                ['shop manage', 'product manage'],
//                ['shop manage', 'order manage'],
                ['manager', 'administrate'],
//                ['manager', 'content manage'],
//                ['manager', 'order manage'],
//                ['manager', 'shop manage'],
//                ['manager', 'category manage'],
//                ['manager', 'product manage'],
//                ['manager', 'property manage'],
                ['manager', 'view manage'],
//                ['manager', 'review manage'],
                ['manager', 'navigation manage'],
//                ['manager', 'form manage'],
//                ['manager', 'media manage'],
                ['admin', 'administrate'],
                ['admin', 'api manage'],
//                ['admin', 'order manage'],
//                ['admin', 'seo manage'],
//                ['admin', 'task manage'],
                ['admin', 'user manage'],
                ['admin', 'cache manage'],
//                ['admin', 'content manage'],
//                ['admin', 'shop manage'],
//                ['admin', 'category manage'],
//                ['admin', 'product manage'],
//                ['admin', 'property manage'],
                ['admin', 'view manage'],
//                ['admin', 'review manage'],
                ['admin', 'navigation manage'],
//                ['admin', 'form manage'],
//                ['admin', 'media manage'],
//                ['admin', 'order status manage'],
//                ['admin', 'payment manage'],
//                ['admin', 'shipping manage'],
//                ['admin', 'monitoring manage'],
//                ['admin', 'newsletter manage'],
//                ['admin', 'data manage'],
                ['admin', 'setting manage'],
            ]
        );
        $this->insert(
            '{{%auth_assignment}}',
            [
                'item_name' => 'admin',
                'user_id' => $user->id,
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_service}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_rule}}');
        $this->dropTable('{{%error_log}}');
        $this->dropTable('{{%notification}}');

    }
}
