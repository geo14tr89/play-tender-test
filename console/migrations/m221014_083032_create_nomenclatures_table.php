<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nomenclatures}}`.
 */
class m221014_083032_create_nomenclatures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nomenclatures}}', [
            'id' => $this->primaryKey(),
            'tender_id' => $this->integer(11)->notNull(),
            'description' => $this->text()->notNull(),
            'count' => $this->integer(11),
            'measure' => $this->integer(2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%nomenclatures}}');
    }
}
