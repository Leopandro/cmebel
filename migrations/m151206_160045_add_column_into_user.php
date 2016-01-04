<?php

use yii\db\Schema;
use yii\db\Migration;

class m151206_160045_add_column_into_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','corporate_email',Schema::TYPE_STRING . ' NOT NULL after email');
    }

    public function down()
    {
        $this->dropColumn('user', 'corporate_email');
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
