<?php
namespace CmsIr\Product\Controller;

use CmsIr\Product\Form\CategoryForm;
use CmsIr\Product\Form\CategoryFormFilter;
use CmsIr\Product\Form\RealizationForm;
use CmsIr\Product\Form\RealizationFormFilter;
use CmsIr\Product\Model\Category;
use CmsIr\Product\Model\Client;
use CmsIr\Product\Model\Product;
use CmsIr\Product\Model\ProductFile;
use CmsIr\Product\Model\Realization;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class RealizationController extends AbstractActionController
{
    protected $productFileUploadDir = 'public/files/product/';
    public function realizationListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name', 'date');

            $listData = $this->getRealizationTable()->getDatatables($columns,$data);

            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
                "aaData" => $listData['aaData']
            );

            $jsonObject = Json::encode($output, true);
            echo $jsonObject;
            return $this->response;
        }

        return new ViewModel();
    }

    public function createRealizationAction()
    {
        $form = new RealizationForm();

        $clients = $this->getClientTable()->getAll();
        $tmpArrayClients = array();
        /**
         * @var $client Client
         */
        foreach ($clients as $client) {
                $tmp = array(
                    'value' => $client->getId(),
                    'label' => $client->getName(),
                );
            array_push($tmpArrayClients, $tmp);
        }

        $form->get('client_id')->setValueOptions($tmpArrayClients);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new RealizationFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $realization = new Realization();

                $realization->exchangeArray($form->getData());
                $this->getRealizationTable()->save($realization);

                $this->flashMessenger()->addMessage('Realizacja została dodana poprawnie.');

                return $this->redirect()->toRoute('realization-list');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editRealizationAction()
    {
        $id = $this->params()->fromRoute('realization_id');
        /**
         * @var $realization Realization
         */
        $realization = $this->getRealizationTable()->getOneBy(array('id' => $id));

        if(!$realization) {
            return $this->redirect()->toRoute('realization-list');
        }

        $clients = $this->getClientTable()->getAll();
        $tmpArrayClients = array();
        /**
         * @var $client Client
         */
        $realizationClientId = $realization->getClientId();

        foreach ($clients as $client) {
            $clientId = $client->getId();
            if($clientId == $realizationClientId)
            {
                $tmp = array(
                    'value' => $clientId,
                    'label' => $client->getName(),
                    'selected' => true
                );
            } else
            {
                $tmp = array(
                    'value' => $clientId,
                    'label' => $client->getName(),
                );
            }

            array_push($tmpArrayClients, $tmp);
        }

        $form = new RealizationForm();
        $form->bind($realization);
        $form->get('client_id')->setValueOptions($tmpArrayClients);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new RealizationFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getRealizationTable()->save($realization);

                $this->flashMessenger()->addMessage('Realizacja została zedytowana poprawnie.');

                return $this->redirect()->toRoute('realization-list');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteRealizationAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('realization_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('realization-list');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $connectedProducts = $this->getProductTable()->getBy(array('id' => $id));

                if($connectedProducts)
                {
                    /**
                     * @var $product Product
                     */
                    foreach($connectedProducts as $product)
                    {
                        $productId = $product->getId();
                        $connectedProductsFiles = $this->getProductFileTable()->getBy(array('product_id' => $productId));

                        if($connectedProductsFiles)
                        {
                            /**
                             * @var $productFile ProductFile
                             */
                            foreach($connectedProductsFiles as $productFile)
                            {
                                $productFileId = $productFile->getId();
                                $productFilename = $productFile->getFilename();

                                if(file_exists($this->productFileUploadDir.$productFilename))
                                    unlink($this->productFileUploadDir.$productFilename);

                                $this->getProductFileTable()->deleteProductFile($productFileId);
                            }
                        }

                        $this->getProductTable()->deleteProduct($productId);
                    }
                }

                $this->getRealizationTable()->deleteRealization($id);
                $this->flashMessenger()->addMessage('Realizacja została usunięta poprawnie.');


                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('realization-list');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getRealizationTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \CmsIr\Product\Model\RealizationTable
     */
    public function getRealizationTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\RealizationTable');
    }

    /**
     * @return \CmsIr\Product\Model\ClientTable
     */
    public function getClientTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\ClientTable');
    }

    /**
     * @return \CmsIr\Product\Model\ProductTable
     */
    public function getProductTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\ProductTable');
    }

    /**
     * @return \CmsIr\Product\Model\ProductFileTable
     */
    public function getProductFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\ProductFileTable');
    }
}