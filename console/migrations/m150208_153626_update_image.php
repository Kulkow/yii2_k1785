<?php

use yii\db\Schema;
use yii\db\Migration;

class m150208_153626_update_image extends Migration
{
    public function up()
    {
        $this->addColumn('{{%image}}', 'ext', Schema::TYPE_STRING. ' NOT NULL');
        $this->addColumn('{{%image}}', 'realname', Schema::TYPE_STRING. ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%image}}', 'ext');
        $this->dropColumn('{{%image}}', 'realname');
        return false;
    }
}
