<?php

use yii\db\Schema;
use yii\db\Migration;

class m151218_100858_add_sku_to_products extends Migration
{
    public function up()
    {
        $this->addColumn('shop_products', 'sku', Schema::TYPE_STRING.'(128) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('shop_products', 'sku');

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
