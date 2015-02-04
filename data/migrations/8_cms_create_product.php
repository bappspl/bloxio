<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateProduct extends AbstractMigration
{

    public function up()
    {
        $this->table('dna_client', array())
            ->addColumn('name', 'string')
            ->addColumn('logo', 'string')
            ->addColumn('size', 'string')
            ->save();

        $this->table('dna_product', array())
             ->addColumn('name', 'string')
             ->addColumn('slug', 'string')
             ->addColumn('date', 'date')
             ->addColumn('url', 'string')
             ->addColumn('main_photo', 'string')
             ->addColumn('client_id', 'integer')
             ->addColumn('description', 'string', array('null'=>true))
             ->addForeignKey('client_id', 'dna_client', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

        $this->table('dna_product_file', array())
            ->addColumn('filename', 'string')
            ->addColumn('product_id', 'integer')
            ->addColumn('size', 'string')
            ->addForeignKey('product_id', 'dna_product', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

    }

    public function down()
    {
        $this->dropTable('dna_client');
        $this->dropTable('dna_product');
        $this->dropTable('dna_product_file');
    }
}