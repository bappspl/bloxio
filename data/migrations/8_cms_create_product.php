<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateProduct extends AbstractMigration
{

    public function up()
    {
        $this->table('dna_category', array())
            ->addColumn('name', 'string')
            ->save();

        $this->table('dna_client', array())
            ->addColumn('name', 'string')
            ->addColumn('filename', 'string', array('null'=>true))
            ->addColumn('description', 'text', array('null'=>true))
            ->addColumn('size', 'string', array('null'=>true))
            ->save();

        $this->table('dna_realization', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('description', 'string', array('null'=>true))
            ->addColumn('date', 'date')
            ->addColumn('client_id', 'integer')
            ->addForeignKey('client_id', 'dna_client', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->table('dna_product', array())
             ->addColumn('name', 'string')
             ->addColumn('slug', 'string')
             ->addColumn('date', 'date')
             ->addColumn('url', 'string', array('null'=>true))
             ->addColumn('main_photo', 'string', array('null'=>true))
             ->addColumn('realization_id', 'integer')
             ->addColumn('category_id', 'integer')
             ->addColumn('description', 'string', array('null'=>true))
             ->addForeignKey('realization_id', 'dna_realization', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('category_id', 'dna_category', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

        $this->table('dna_product_file', array())
            ->addColumn('filename', 'string')
            ->addColumn('product_id', 'integer')
            ->addColumn('size', 'string')
            ->addColumn('mime_type', 'string')
            ->addForeignKey('product_id', 'dna_product', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

    }

    public function down()
    {
        $this->dropTable('dna_category');
        $this->dropTable('dna_client');
        $this->dropTable('dna_realization');
        $this->dropTable('dna_product');
        $this->dropTable('dna_product_file');
    }
}