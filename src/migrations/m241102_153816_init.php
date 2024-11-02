<?php

use yii\db\Migration;

/**
 * Class m241102_153816_init
 */
class m241102_153816_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = file_get_contents(Yii::getAlias('@dbfiles').'/init.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        while ($command->pdoStatement->nextRowSet()) {}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241102_153816_init cannot be reverted.\n";

        return false;
    }
}
