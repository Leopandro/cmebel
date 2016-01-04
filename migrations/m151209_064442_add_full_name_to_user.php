<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_064442_add_full_name_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','full_name',Schema::TYPE_STRING . ' NOT NULL AFTER username');
    }

    public function down()
    {
        $table = $this->db->schema->getTableSchema('user');
        if (isset($table->columns['full_name']))
            $this->dropColumn('user', 'full_name');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
