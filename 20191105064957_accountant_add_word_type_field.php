<?php

use Phinx\Migration\AbstractMigration;

class AccountantAddWordTypeField extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $rows = $this->get_rollover_ids();

        foreach ($rows as $row) {
            $rollover_id = $row['id'];

            if ($rollover_id) {
                $table = $this->table('template_' . $rollover_id);
            } else {
                $table = $this->table('template');
            }

            $has_wt = $table->hasColumn('wording_type');
            if (!$has_wt) {
                $table->addColumn('wording_type', 'string', ['limit' => 30, 'null' => true]);
            }

            $has_des = $table->hasColumn('des');
            if (!$has_des) {
                $table->addColumn('des', 'text', ['null' => true]);
            }

            $table->update();
        }
    }

    public function down()
    {
        $rows = $this->get_rollover_ids();

        foreach ($rows as $row) {
            $rollover_id = $row['id'];

            if ($rollover_id) {
                $table = $this->table('template_' . $rollover_id);
            } else {
                $table = $this->table('template');
            }

            $has_wt = $table->hasColumn('wording_type');
            if ($has_wt) {
                $table->removeColumn('wording_type');
            }

            $has_des = $table->hasColumn('des');
            if ($has_des) {
                $table->removeColumn('des');
            }

            $table->update();
        }
    }

    private function get_rollover_ids()
    {
        // get all rollover id
        $result = $this->query('SELECT id FROM rollover_control ORDER BY id DESC');
        $rows   = $result->fetchAll();
        return $rows;
    }
}
