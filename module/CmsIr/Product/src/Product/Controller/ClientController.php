<?php
namespace CmsIr\Product\Controller;

use CmsIr\Product\Form\ClientForm;
use CmsIr\Product\Form\ClientFormFilter;
use CmsIr\Product\Model\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class ClientController extends AbstractActionController
{
    protected $uploadDir = 'public/files/client/';

    public function clientListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getClientTable()->getDatatables($columns,$data);

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

    public function createClientAction()
    {
        $form = new ClientForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new ClientFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $client = new Client();

                $client->exchangeArray($form->getData());

                $file = $client->getFilename();
                $fileSize = filesize($this->uploadDir.'/'.$file);
                $client->setSize($fileSize);

                $this->getClientTable()->save($client);

                $this->flashMessenger()->addMessage('Klient został dodany poprawnie.');

                return $this->redirect()->toRoute('client-list');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editClientAction()
    {
        $id = $this->params()->fromRoute('client_id');
        /**
         * @var $client Client
         */
        $client = $this->getClientTable()->getOneBy(array('id' => $id));

        if(!$client) {
            return $this->redirect()->toRoute('client-list');
        }

        $form = new ClientForm();
        $form->bind($client);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new ClientFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $file = $client->getFilename();
                $fileSize = filesize($this->uploadDir.'/'.$file);
                $client->setSize($fileSize);

                $this->getClientTable()->save($client);

                $this->flashMessenger()->addMessage('Klient został zedytowany poprawnie.');

                return $this->redirect()->toRoute('client-list');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteClientAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('client_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('client-list');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $clientConnectedWithRealizations = $this->getRealizationTable()->getBy(array('id' => $id));
                if(!empty($clientConnectedWithRealizations))
                {
                    $realizations = array();
                    foreach($clientConnectedWithRealizations as $client)
                    {
                        $realizations[$client->getId()] = $client->getName();
                    }
                    $this->flashMessenger()->addErrorMessage('Klient nie może być usunięty, ponieważ jest przypisany do realizacji: ' . implode(', ', $realizations) . '.');

                } else
                {
                    /**
                     * @var $client Client
                     */
                    $client = $this->getClientTable()->getOneBy(array('id' => $id));
                    $filename = $client->getFilename();

                    if(file_exists($this->uploadDir.$filename))
                        unlink($this->uploadDir.$filename);

                    $this->getClientTable()->deleteClient($id);
                    $this->flashMessenger()->addMessage('Klient został usunięty poprawnie.');
                }

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('client-list');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getClientTable()->getOneBy(array('id' => $id))
        );
    }

    public function uploadAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
    }

    /**
     * @return \CmsIr\Product\Model\ClientTable
     */
    public function getClientTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\ClientTable');
    }

    /**
     * @return \CmsIr\Product\Model\RealizationTable
     */
    public function getRealizationTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\RealizationTable');
    }
}