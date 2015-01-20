<?php

use yii\db\Schema;
use yii\db\Migration;

class m150120_183418_nomenclature extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
 
        $this->createTable('{{%nomenclature}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'alias' => Schema::TYPE_STRING . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER,
            'price' => Schema::TYPE_STRING . ' NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'image_id' => Schema::TYPE_INTEGER,
            'sort' => Schema::TYPE_INTEGER,
        ], $tableOptions);
 
        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'alias' => Schema::TYPE_STRING . ' NOT NULL',
            'anons' => Schema::TYPE_TEXT . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER,
        ], $tableOptions);
        
        $this->createTable('{{%image}}', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'path' => Schema::TYPE_TEXT . ' NOT NULL',
            'alt' => Schema::TYPE_TEXT . ' NOT NULL',
            'hide' => Schema::TYPE_TEXT,
            'timestamp' => Schema::TYPE_INTEGER,
        ], $tableOptions);
        
        $this->createIndex('FK_nomenclature_image', '{{%nomenclature}}', 'image_id');
        $this->addForeignKey(
            'FK_nomenclature_image', '{{%nomenclature}}', 'image_id', '{{%image}}', 'id', 'SET NULL', 'CASCADE'
        );
 
        $this->createIndex('FK_nomenclature_category', '{{%nomenclature}}', 'category_id');
        $this->addForeignKey(
            'FK_nomenclature_category', '{{%nomenclature}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'CASCADE'
        );
 
    }

    public function down()
    {
        $this->dropTable('{{%nomenclature}}');
        $this->dropTable('{{%image}}');
        $this->dropTable('{{%category}}');
    }
}
