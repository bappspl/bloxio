<?php
namespace CmsIr\Product\Controller;

use CmsIr\Product\Form\CategoryForm;
use CmsIr\Product\Form\CategoryFormFilter;
use CmsIr\Product\Model\Category;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class CategoryController extends AbstractActionController
{
    public function categoryListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getCategoryTable()->getDatatables($columns,$data);

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

    public function createCategoryAction()
    {
        $form = new CategoryForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new CategoryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $page = new Category();

                $page->exchangeArray($form->getData());
                $this->getCategoryTable()->save($page);

                $this->flashMessenger()->addMessage('Kategoria została dodana poprawnie.');

                return $this->redirect()->toRoute('category');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editCategoryAction()
    {
        $id = $this->params()->fromRoute('category_id');

        $category = $this->getCategoryTable()->getOneBy(array('id' => $id));

        if(!$category) {
            return $this->redirect()->toRoute('category');
        }

        $form = new CategoryForm();
        $form->bind($category);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new CategoryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCategoryTable()->save($category);

                $this->flashMessenger()->addMessage('Kategoria została edytowana poprawnie.');

                return $this->redirect()->toRoute('category');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteCategoryAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('category_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $categoryConnectedWithProducts = $this->getProductTable()->getBy(array('id' => $id));
                if(!empty($categoryConnectedWithProducts))
                {
                    $products = array();
                    foreach($categoryConnectedWithProducts as $product)
                    {
                        $products[$product->getId()] = $product->getName();
                    }
                    $this->flashMessenger()->addErrorMessage('Kategoria nie może być usunięta, ponieważ jest przypisana do produktów: ' . implode(', ', $products) . '.');

                } else
                {
                    $this->getCategoryTable()->deleteCategory($id);
                    $this->flashMessenger()->addMessage('Kategoria została usunięta poprawnie.');
                }

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('category');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getCategoryTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \CmsIr\Product\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\CategoryTable');
    }

    /**
     * @return \CmsIr\Product\Model\ProductTable
     */
    public function getProductTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\ProductTable');
    }
}