<?php

use yii\db\Migration;

class m250603_172302_short_url extends Migration
{
    public function up()
    {
        $this->createTable('url', [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull(),
            'short_code' => $this->string(10)->notNull()->unique(),
            'created_at' => $this->integer(),
            'clicks' => $this->integer()->defaultValue(0),
        ]);

        $this->createTable('url_log', [
            'id' => $this->primaryKey(),
            'url_id' => $this->integer()->notNull(),
            'ip' => $this->string(45),
            'visited_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-url_log-url_id',
            'url_log',
            'url_id',
            'url',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-url_log-url_id', 'url_log');
        $this->dropTable('url_log');
        $this->dropTable('url');
    }
}