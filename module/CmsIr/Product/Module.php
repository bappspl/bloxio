<?php
namespace CmsIr\Product;

// Add this for Table Date Gateway
use CmsIr\Product\Model\Category;
use CmsIr\Product\Model\CategoryTable;
use CmsIr\Product\Model\Client;
use CmsIr\Product\Model\ClientTable;
use CmsIr\Product\Model\Product;
use CmsIr\Product\Model\ProductFile;
use CmsIr\Product\Model\ProductFileTable;
use CmsIr\Product\Model\ProductTable;
use CmsIr\Product\Model\Realization;
use CmsIr\Product\Model\RealizationTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $viewModel = $e->getViewModel();

        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $viewModel->loggedUser = $loggedUser;
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/Product',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\Product\Model\ProductTable' =>  function($sm) {
                        $tableGateway = $sm->get('ProductTableGateway');
                        $table = new ProductTable($tableGateway);
                        return $table;
                    },
                'ProductTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Product());
                        return new TableGateway('dna_product', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Product\Model\CategoryTable' =>  function($sm) {
                        $tableGateway = $sm->get('CategoryTableGateway');
                        $table = new CategoryTable($tableGateway);
                        return $table;
                    },
                'CategoryTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Category());
                        return new TableGateway('dna_category', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Product\Model\ClientTable' =>  function($sm) {
                        $tableGateway = $sm->get('ClientTableGateway');
                        $table = new ClientTable($tableGateway);
                        return $table;
                    },
                'ClientTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Client());
                        return new TableGateway('dna_client', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Product\Model\RealizationTable' =>  function($sm) {
                        $tableGateway = $sm->get('RealizationTableGateway');
                        $table = new RealizationTable($tableGateway);
                        return $table;
                    },
                'RealizationTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Realization());
                        return new TableGateway('dna_realization', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Product\Model\ProductFileTable' =>  function($sm) {
                        $tableGateway = $sm->get('ProductFileTableGateway');
                        $table = new ProductFileTable($tableGateway);
                        return $table;
                    },
                'ProductFileTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new ProductFile());
                        return new TableGateway('dna_product_file', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }
}