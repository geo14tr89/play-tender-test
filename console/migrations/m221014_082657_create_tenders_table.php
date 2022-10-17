<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tenders}}`.
 */
class m221014_082657_create_tenders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tenders}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'budget' => $this->integer(10)->notNull(),
            'status' => $this->integer(2)->notNull(),
            'created_by' => $this->integer(10)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tenders}}');
    }
}
