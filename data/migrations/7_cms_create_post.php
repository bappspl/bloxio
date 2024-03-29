<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePost extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_post', array())
             ->addColumn('name', 'string')
             ->addColumn('url', 'string')
             ->addColumn('status_id', 'integer')
             ->addColumn('author_id', 'integer')
             ->addColumn('category', 'string', array('null'=>true))
             ->addColumn('text', 'text')
             ->addColumn('date', 'date')
             ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('author_id', 'cms_users', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

        $this->table('cms_post_file', array())
            ->addColumn('filename', 'string')
            ->addColumn('post_id', 'integer')
            ->addColumn('size', 'string')
            ->addForeignKey('post_id', 'cms_post', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();
    }

    public function down()
    {
        $this->dropTable('cms_post');
        $this->dropTable('cms_post_file');
    }
}