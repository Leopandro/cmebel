<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_093724_add_columns_to_orders extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'comment', Schema::TYPE_STRING.'(128) NOT NULL');
        $this->addColumn('orders', 'payment_method', Schema::TYPE_STRING.'(255) NOT NULL');
        $this->addColumn('orders', 'payment_address_1', Schema::TYPE_STRING.'(255) NOT NULL');
        $this->addColumn('orders', 'payment_address_2', Schema::TYPE_STRING.'(255) NOT NULL');
        $this->addColumn('orders', 'payment_city', Schema::TYPE_STRING.'(255) NOT NULL');
        $this->addColumn('orders', 'payment_country', Schema::TYPE_STRING.'(255) NOT NULL');
        $this->addColumn('orders', 'payment_postcode', Schema::TYPE_INTEGER.'(10) NOT NULL');
        $this->addColumn('orders', 'payment_zone', Schema::TYPE_INTEGER.'(10) NOT NULL');
    }

    public function down()
    {
        $table = $this->db->schema->getTableSchema('orders');
        if (isset($table->columns['comment']))
            $this->dropColumn('orders', 'comment');

        if (isset($table->columns['payment_method']))
            $this->dropColumn('orders', 'payment_method');

        if (isset($table->columns['payment_address_1']))
            $this->dropColumn('orders', 'payment_address_1');

        if (isset($table->columns['payment_address_1']))
            $this->dropColumn('orders', 'payment_address_1');

        if (isset($table->columns['payment_address_2']))
            $this->dropColumn('orders', 'payment_address_2');

        if (isset($table->columns['payment_city']))
            $this->dropColumn('orders', 'payment_city');

        if (isset($table->columns['payment_country']))
            $this->dropColumn('orders', 'payment_country');

        if (isset($table->columns['payment_postcode']))
            $this->dropColumn('orders', 'payment_postcode');

        if (isset($table->columns['payment_zone']))
            $this->dropColumn('orders', 'payment_zone');

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
