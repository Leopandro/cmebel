<?php

use yii\db\Schema;
use yii\db\Migration;

class m151221_103618_change_type_postcode_country extends Migration
{
    public function up()
    {
        $this->alterColumn('orders', 'payment_postcode', Schema::TYPE_STRING.'(10) NOT NULL');
        $this->alterColumn('orders', 'payment_zone', Schema::TYPE_STRING.'(128) NOT NULL');
    }

    public function down()
    {
        $this->alterColumn('orders', 'payment_postcode', Schema::TYPE_INTEGER.'(10) NOT NULL');
        $this->alterColumn('orders', 'payment_zone', Schema::TYPE_INTEGER.'(10) NOT NULL');
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
