<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePage extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_page', array())
             ->addColumn('name', 'string')
             ->addColumn('slug', 'string')
             ->addColumn('status_id', 'integer')
             ->addColumn('content', 'text', array('null'=>true))
             ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

            $this->insertValues('cms_page', array(
                    'name' => 'string',
                    'slug' => 'string',
                    'status_id' => 'integer',
                    'content' => 'text',
                )
            );
    }

    public function insertValues($tableName, $tableColumns)
    {
        $path = fopen ('./data/fixtures/'.$tableName.'.csv',"r");
        while (($data = fgetcsv($path, 1000, ",")) !== FALSE)  {
            $value = '';
            $i = 0;
            foreach ($tableColumns as $kCol => $vCol) {
                switch ($vCol) {
                    case 'text':
                        $value = $value . $kCol.' = "'.iconv("UTF-8", "ISO-8859-1//TRANSLIT", $data[$i]). '", ';
                        break;
                    case 'string':
                        $value = $value . $kCol.' = "'.iconv("UTF-8", "ISO-8859-1//TRANSLIT", $data[$i]). '", ';
                        break;
                    case 'integer':
                        $value = $value . $kCol.' = '.iconv("UTF-8", "ISO-8859-1//TRANSLIT", $data[$i]) . ', ';
                        break;
                }
                $i++;
            }
            $realValue = substr($value, 0, -2);
            $this->adapter->execute('insert into '.$tableName.' set '.$realValue);
        }
        fclose ($path);
    }

    public function down()
    {
        $this->dropTable('cms_page');
    }
}