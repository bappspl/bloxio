<?php

namespace Page\Controller;

use CmsIr\Newsletter\Model\Subscriber;
use CmsIr\Post\Model\Post;
use CmsIr\Product\Model\Client;
use CmsIr\Product\Model\Product;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Json\Json;
use Zend\Mail\Message;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class PageController extends AbstractActionController
{
    public function homeAction()
    {
        $this->layout('layout/home');
        // home page content
        $page = $this->getPageService()->findOneBySlug('home');
        if(empty($page)){
            $this->getResponse()->setStatusCode(404);
        }

        // clients for footer
        $clients = $this->getClientTable()->getAll();
        $this->layout()->clients = $clients;

        $categories = $this->getCategoryTable()->getAll();

        // products
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $pageNumber = (int) $request->getPost('page');

            $order = 'date DESC';
            $countProducts = $this->getProductTable()->getByAndCount(array());
            $products = $this->getProductTable()->getWithPaginationBy(new Product(), array(), $order);
            $products->setCurrentPageNumber($pageNumber);
            $products->setItemCountPerPage(6);

            $portfolioTemplate = $this->getPortfolioTemplate($products);
            $params = array(
                'nextPage' => $pageNumber += 1,
                'products' => $portfolioTemplate,
                'countProducts' => $countProducts

            );
            $jsonObject = Json::encode($params, true);
            echo $jsonObject;
            return $this->response;
        } else
        {
            $pageNumber = 1;
            $order = 'date DESC';
            $countProducts = $this->getProductTable()->getByAndCount(array());
            $products = $this->getProductTable()->getWithPaginationBy(new Product(), array(), $order);
            $products->setCurrentPageNumber($pageNumber);
            $products->setItemCountPerPage(6);

            $test = array();

            /* @var $product Product */
            foreach($products as $product)
            {
                $realizationId = $product->getRealizationId();
                $categoryId = $product->getCategoryId();

                $realization = $this->getRealizationTable()->getOneBy(array('id' => $realizationId));
                $product->setRealization($realization->getName());

                $category = $this->getCategoryTable()->getOneBy(array('id' => $categoryId));
                $product->setCategory($category->getName());
                $test[] = $product;
            }
        }
        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['products'] = $test;
        $viewParams['countProducts'] = $countProducts;
        $viewParams['categories'] = $categories;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function productDescriptionAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $productId = (int) $request->getPost('productId');
            /* @var $product Product */
            $product = $this->getProductTable()->getOneBy(array('id' => $productId));

            $template = "";
            $template .= "<div class='cbp-l-inline'>";
            $template .= "<div class='cbp-l-inline-left GrayScale'>";
            $template .= "<img src='/thumb/product/502x376/".$product->getMainPhoto()."' alt=''>";
            $template .= "</div>";
            $template .= "<div class='cbp-l-inline-right'>";
            $template .= "<div class='cbp-l-inline-title'>".$product->getName()."</div>";
            $template .= "<div class='cbp-l-inline-desc'>".$product->getDescription()."</div>";
            $template .= "<a href='/strona/portfolio/".$product->getSlug()."' class='cbp-l-inline-view'>Zobacz projekt</a>";
            $template .= "</div></div>";

            $params = array('content' => $template);

            $jsonObject = Json::encode($params, true);
            echo $jsonObject;
            return $this->response;

        }
        return false;
    }
    public function viewPageAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');
        if($slug == 'klient')
            $page = $this->getPageService()->findOneBySlug('portfolio');
        else
            $page = $this->getPageService()->findOneBySlug($slug);

        if(empty($page)){
            $this->getResponse()->setStatusCode(404);
        }
        $viewParams = array();

        switch($slug)
        {
            case 'portfolio':
                $post = $this->params('post');
                if(isset($post))
                {
                    /* @var $product Product */
                    $product = $this->getProductTable()->getOneBy(array('slug' => $post));
                    $productId = $product->getId();
                    $productFiles = $this->getProductFileTable()->getBy(array('product_id' => $productId));

                    $realizationId = $product->getRealizationId();
                    $categoryId = $product->getCategoryId();

                    $realization = $this->getRealizationTable()->getOneBy(array('id' => $realizationId));
                    $client = $this->getClientTable()->getOneBy(array('id' => $realization->getClientId()));
                    $realization->setClient($client);

                    $product->setRealization($realization);

                    $category = $this->getCategoryTable()->getOneBy(array('id' => $categoryId));
                    $product->setCategory($category->getName());

                    $viewParams['post'] = $post;
                    $viewParams['product'] = $product;
                    $viewParams['productFiles'] = $productFiles;
                } else
                {
                    $categories = $this->getCategoryTable()->getAll();

                    // products
                    $request = $this->getRequest();
                    if ($request->isPost())
                    {
                        $pageNumber = (int) $request->getPost('page');

                        $order = 'date DESC';
                        $countProducts = $this->getProductTable()->getByAndCount(array());
                        $products = $this->getProductTable()->getWithPaginationBy(new Product(), array(), $order);
                        $products->setCurrentPageNumber($pageNumber);
                        $products->setItemCountPerPage(6);

                        $portfolioTemplate = $this->getPortfolioTemplate($products);
                        $params = array(
                            'nextPage' => $pageNumber += 1,
                            'products' => $portfolioTemplate,
                            'countProducts' => $countProducts

                        );
                        $jsonObject = Json::encode($params, true);
                        echo $jsonObject;
                        return $this->response;
                    } else
                    {
                        $pageNumber = 1;
                        $order = 'date DESC';
                        $countProducts = $this->getProductTable()->getByAndCount(array());
                        $products = $this->getProductTable()->getWithPaginationBy(new Product(), array(), $order);
                        $products->setCurrentPageNumber($pageNumber);
                        $products->setItemCountPerPage(6);

                        $test = array();

                        /* @var $product Product */
                        foreach($products as $product)
                        {
                            $realizationId = $product->getRealizationId();
                            $categoryId = $product->getCategoryId();

                            $realization = $this->getRealizationTable()->getOneBy(array('id' => $realizationId));
                            $product->setRealization($realization->getName());

                            $category = $this->getCategoryTable()->getOneBy(array('id' => $categoryId));
                            $product->setCategory($category->getName());
                            $test[] = $product;
                        }
                    }

                    $viewParams['products'] = $test;
                    $viewParams['countProducts'] = $countProducts;
                    $viewParams['categories'] = $categories;
                }

            break;
            case 'klient':
                $post = $this->params('post');
                if(isset($post))
                {
                    /* @var $client Client */
                    $client = $this->getClientTable()->getOneBy(array('slug' => $post));
                    $clientId = $client->getId();

                    $clientRealizations = $this->getRealizationTable()->getBy(array('client_id' => $clientId));
                    $realizationIds = array();
                    foreach($clientRealizations as $realization)
                    {
                        $realizationIds[] = $realization->getId();
                    }

                    // categories
                    $allProductsIn = $this->getProductTable()->getBy(new In('realization_id', $realizationIds));
                    $categoryIds = array();
                    foreach($allProductsIn as $product)
                    {
                        $categoryIds[] = $product->getCategoryId();
                    }
                    $categories = $this->getCategoryTable()->getBy(new In('id', $categoryIds));

                    // products
                    $request = $this->getRequest();
                    if ($request->isPost())
                    {
                        $pageNumber = (int) $request->getPost('page');

                        $order = 'date DESC';
                        $countProducts = $this->getProductTable()->getByAndCount(new In('realization_id', $realizationIds));
                        $products = $this->getProductTable()->getWithPaginationBy(new Product(), new In('realization_id', $realizationIds), $order);
                        $products->setCurrentPageNumber($pageNumber);
                        $products->setItemCountPerPage(6);

                        $portfolioTemplate = $this->getPortfolioTemplate($products);
                        $params = array(
                            'nextPage' => $pageNumber += 1,
                            'products' => $portfolioTemplate,
                            'countProducts' => $countProducts

                        );
                        $jsonObject = Json::encode($params, true);
                        echo $jsonObject;
                        return $this->response;
                    } else
                    {

                        $pageNumber = 1;
                        $order = 'date DESC';
                        $countProducts = $this->getProductTable()->getByAndCount(new In('realization_id', $realizationIds));
                        $products = $this->getProductTable()->getWithPaginationBy(new Product(), new In('realization_id', $realizationIds), $order);
                        $products->setCurrentPageNumber($pageNumber);
                        $products->setItemCountPerPage(6);

                        $test = array();

                        /* @var $product Product */
                        foreach($products as $product)
                        {
                            $realizationId = $product->getRealizationId();
                            $categoryId = $product->getCategoryId();

                            $realization = $this->getRealizationTable()->getOneBy(array('id' => $realizationId));
                            $product->setRealization($realization->getName());

                            $category = $this->getCategoryTable()->getOneBy(array('id' => $categoryId));
                            $product->setCategory($category->getName());
                            $test[] = $product;
                        }
                    }

                    $viewParams['client'] = $client;
                    $viewParams['products'] = $test;
                    $viewParams['countProducts'] = $countProducts;
                    $viewParams['categories'] = $categories;
                }
            break;
            case 'o-nas':
                $users = $this->getUsersTable()->getAll();
                $viewParams['users'] = $users;

            break;
            case 'kontakt':

            break;
        }

        $clients = $this->getClientTable()->getAll();
        $this->layout()->clients = $clients;

        $viewParams['page'] = $page;
        $viewParams['slug'] = $slug;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;

    }

    public function viewNewsAction ()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();
        $allNews = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'news'), 'date DESC');

        /* @var $news \CmsIr\Post\Model\Post */

        $pageNumber = $this->params()->fromRoute('number') ? (int) $this->params()->fromRoute('number') : 1;
        $allNews->setCurrentPageNumber($pageNumber);
        $allNews->setItemCountPerPage(2);

        $test = array();

        foreach($allNews as $news)
        {
            $newsId = $news->getId();
            $authorId = $news->getAuthorId();
            $newsFiles = $this->getPostFileTable()->getBy(array('post_id' => $newsId));
            $author = $this->getUsersTable()->getOneBy(array('id' => $authorId));

            $news->setAuthor($author->getName());
            $news->setFiles($newsFiles);
            $test[] = $news;

        }

        $page = $this->getPageService()->findOneBySlug('aktualnosci');

        $viewParams['page'] = $page;
        $viewParams['news'] = $test;
        $viewParams['paginator'] = $allNews;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function oneNewsAction()
    {
        $this->layout('layout/home');
        $slug = $this->params('slug');
        /* @var $news \CmsIr\Post\Model\Post */

        $news = $this->getPostTable()->getOneBy(array('url' => $slug));
        $newsId = $news->getId();
        $authorId = $news->getAuthorId();

        $author = $this->getUsersTable()->getOneBy(array('id' => $authorId));
        $newsFiles = $this->getPostFileTable()->getBy(array('post_id' => $newsId));

        $news->setFiles($newsFiles);
        $news->setAuthor($author->getName());

        $page = $this->getPageService()->findOneBySlug('aktualnosci');

        $viewParams['page'] = $page;
        $viewParams['post'] = $news;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function contactFormAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $name = $request->getPost('name');
            $email = $request->getPost('email');
            $website = $request->getPost('website');
            $subject = $request->getPost('subject');
            $comments = $request->getPost('comments');

            $content = "Imię: <b>" . $name . "</b> <br/>" .
                       "Email: <b>" . $email . "</b> <br/>" .
                       "Strona www: <b>" . $website . "</b> <br/>" .
                       "Temat: <b>" . $subject . "</b> <br/>" .
                       "Treść: <b>" . $comments . "</b> <br/>";

            $html = new MimePart($content);
            $html->type = "text/html";

            $body = new MimeMessage();
            $body->setParts(array($html));

            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();
            $this->getRequest()->getServer();
            $message->addTo('website@dnastudio.pl')
                    ->addFrom('website@dnastudio.pl')
                    ->setEncoding('UTF-8')
                    ->setSubject('Wiadomość z formularza kontaktowego')
                    ->setBody($body);
            $transport->send($message);
        }


        $params = 'Wiadomość została wysłana poprawnie';
        $jsonObject = Json::encode($params, true);
        echo $jsonObject;
        return $this->response;
    }

    public function captchaAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $realPerson = $request->getPost('realPerson');
            $realPersonHash = $request->getPost('realPersonHash');

            if ($this->rpHash($realPerson) == $realPersonHash)
            {
                $params['status'] = 'success';
            } else
            {
                $params['status'] = 'error';
            }

            $jsonObject = Json::encode($params, true);
            echo $jsonObject;
            return $this->response;
        }

        return false;
    }

    public function saveSubscriberAjaxAction ()
    {
        $request = $this->getRequest();


        if ($request->isPost()) {
            $uncofimerdStatus = $this->getStatusTable()->getOneBy(array('slug' => 'unconfirmed'));
            $uncofimerdStatusId = $uncofimerdStatus->getId();

            $email = $request->getPost('email');
            $confirmationCode = uniqid();
            $subscriber = new Subscriber();
            $subscriber->setEmail($email);
            $subscriber->setGroups(array());
            $subscriber->setConfirmationCode($confirmationCode);
            $subscriber->setStatusId($uncofimerdStatusId);

            $this->getSubscriberTable()->save($subscriber);
            $this->sendConfirmationEmail($email, $confirmationCode);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }

    public function sendConfirmationEmail($email, $confirmationCode)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($email)
            ->addFrom('website@dnastudio.pl')
            ->setSubject('Prosimy o potwierdzenie subskrypcji!')
            ->setBody("W celu potwierdzenia subskrypcji kliknij w link => " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                $this->url()->fromRoute('newsletter-confirmation', array('code' => $confirmationCode)));
        $transport->send($message);
    }

    public function confirmationNewsletterAction()
    {
        $this->layout('layout/home');
        $request = $this->getRequest();
        $code = $this->params()->fromRoute('code');
        if (!$code) {
            return $this->redirect()->toRoute('home');
        }

        $viewParams = array();
        $viewModel = new ViewModel();

        $subscriber = $this->getSubscriberTable()->getOneBy(array('confirmation_code' => $code));

        $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
        $confirmedStatusId = $confirmedStatus->getId();

        if($subscriber == false)
        {
            $viewParams['message'] = 'Nie istnieje taki użytkownik';
            $viewModel->setVariables($viewParams);
            return $viewModel;
        }

        $subscriberStatus = $subscriber->getStatusId();

        if($subscriberStatus == $confirmedStatusId)
        {
            $viewParams['message'] = 'Użytkownik już potwierdził subskrypcję';
        }

        else
        {
            $viewParams['message'] = 'Subskrypcja została potwierdzona';
            $subscriberGroups = $subscriber->getGroups();
            $groups = unserialize($subscriberGroups);

            $subscriber->setStatusId($confirmedStatusId);
            $subscriber->setGroups($groups);
            $this->getSubscriberTable()->save($subscriber);
        }

        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    private function getPortfolioTemplate($products)
    {
         /* @var $product \CmsIr\Product\Model\Product */
        $template = '';
        foreach($products as $product)
        {
            $name = $product->getName();
            $filename = $product->getMainPhoto();
            $realizationId = $product->getRealizationId();
            $categoryId = $product->getCategoryId();

            $realization = $this->getRealizationTable()->getOneBy(array('id' => $realizationId));
            $category = $this->getCategoryTable()->getOneBy(array('id' => $categoryId));

            $template .= "<li class='cbp-item " . $categoryId . "'>";
            $template .= "<a href='#' class='cbp-caption cbp-singlePageInline' data-title='" .$name."'>";
            $template .= "<div class='cbp-caption-defaultWrap GrayScale'>";
            $template .= "<img src='thumb/product/373x273/". $filename ."' alt='". $name . "'>";
            $template .= "</div>";
            $template .= "<div class='cbp-caption-activeWrap'><div class='cbp-l-caption-alignLeft'><div class='cbp-l-caption-body'>";
            $template .= "<div class='cbp-l-caption-title'>" .$name."</div>";
            $template .= "<div class='cbp-l-caption-desc'>". $realization->getName() . ", " . $category->getName() . "</div>";
            $template .= "</div></div></div></a></li>";
        }

        return $template;
    }

    private function rpHash($value) {
        $hash = 5381;
        $value = strtoupper($value);
        for($i = 0; $i < strlen($value); $i++) {
            $hash = ($this->leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
        }
        return $hash;
    }

    private function leftShift32($number, $steps) {
        // convert to binary (string)
        $binary = decbin($number);
        // left-pad with 0's if necessary
        $binary = str_pad($binary, 32, "0", STR_PAD_LEFT);
        // left shift manually
        $binary = $binary.str_repeat("0", $steps);
        // get the last 32 bits
        $binary = substr($binary, strlen($binary) - 32);
        // if it's a positive number return it
        // otherwise return the 2's complement
        return ($binary{0} == "0" ? bindec($binary) :
            -(pow(2, 31) - bindec(substr($binary, 1))));
    }
    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Slider\Service\SliderService
     */
    public function getSliderService()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Service\SliderService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Service\PageService');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
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
     * @return \CmsIr\Product\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\CategoryTable');
    }

    /**
     * @return \CmsIr\Product\Model\RealizationTable
     */
    public function getRealizationTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\RealizationTable');
    }

    /**
     * @return \CmsIr\Product\Model\ProductFileTable
     */
    public function getProductFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\ProductFileTable');
    }

    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Users\Model\UsersTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostFileTable
     */
    public function getPostFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostFileTable');
    }
}
