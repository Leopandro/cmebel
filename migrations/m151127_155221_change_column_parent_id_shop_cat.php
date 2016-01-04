<?php

use yii\db\Migration;

class m151127_155221_change_column_parent_id_shop_cat extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_categories','parent_id','integer NULL DEFAULT NULL');

        $this->update('shop_categories',['parent_id'=>null],['parent_id'=>0]);

    }

    public function down()
    {
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
