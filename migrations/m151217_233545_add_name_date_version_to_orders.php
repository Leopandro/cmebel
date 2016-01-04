<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_233545_add_name_date_version_to_orders extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'version_name', Schema::TYPE_STRING.'(128) NOT NULL');
        $this->addColumn('orders', 'version_date', Schema::TYPE_DATETIME.'(50) NOT NULL');
    }

    public function down()
    {

        $this->dropColumn('orders', 'version_name');
        $this->dropColumn('orders', 'version_date');

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
