<?php
namespace CmsIr\Product\Controller;

use CmsIr\Product\Form\ProductForm;
use CmsIr\Product\Form\ProductFormFilter;
use CmsIr\Product\Model\Category;
use CmsIr\Product\Model\Product;
use CmsIr\Product\Model\ProductFile;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class ProductController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/product/';
    protected $destinationUploadDir = 'public/files/product/';

    public function productListAction()
    {
        $realizationId = $this->params()->fromRoute('realization_id');
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name', 'date');

            $listData = $this->getProductTable()->getProductDatatables($columns, $data, $realizationId);

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

        $viewParams = array();
        $viewParams['realizationId'] = $realizationId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createProductAction()
    {
        $realizationId = $this->params()->fromRoute('realization_id');

        $form = new ProductForm();
        $categories = $this->getCategoryTable()->getAll();
        $tmpArrayCategories = array();
        /**
         * @var $category Category
         */
        foreach ($categories as $category) {
            $tmp = array(
                'value' => $category->getId(),
                'label' => $category->getName(),
            );
            array_push($tmpArrayCategories, $tmp);
        }

        $form->get('category_id')->setValueOptions($tmpArrayCategories);

        $request = $this->getRequest();

        if ($request->isPost())
        {

            $form->setInputFilter(new ProductFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $product = new Product();
                $product->exchangeArray($form->getData());


                $productId = $this->getProductTable()->save($product);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $fileSize = filesize($this->uploadDir.'/'.$file);
                        $mimeType = $this->getMimeTypeFromPath($this->uploadDir.'/'.$file);

                        $productFile = new ProductFile();
                        $productFile->setFilename($file);
                        $productFile->setProductId($productId);
                        $productFile->setSize($fileSize);
                        $productFile->setMimeType($mimeType);

                        $this->getProductFileTable()->save($productFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->flashMessenger()->addMessage('Produkt został dodany poprawnie.');

                return $this->redirect()->toRoute('realization/product', array('realization_id' => $realizationId));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['realizationId'] = $realizationId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editProductAction()
    {
        $realizationId = $this->params()->fromRoute('realization_id');
        $productId = $this->params()->fromRoute('product_id');

        /**
         * @var $product Product
         */
        $product = $this->getProductTable()->getOneBy(array('id' => $productId));
        $productFiles = $this->getProductFileTable()->getBy(array('product_id' => $productId));

        $productCategoryId = $product->getCategoryId();

        $categories = $this->getCategoryTable()->getAll();
        $tmpArrayCategories = array();
        /**
         * @var $category Category
         */
        foreach ($categories as $category) {
            $categoryId = $category->getId();
            if($categoryId == $productCategoryId)
            {
                $tmp = array(
                    'value' => $categoryId,
                    'label' => $category->getName(),
                    'selected' => true
                );
            } else
            {
                $tmp = array(
                    'value' => $categoryId,
                    'label' => $category->getName(),
                );
            }

            array_push($tmpArrayCategories, $tmp);
        }
        $form = new ProductForm();
        $form->bind($product);
        $form->get('category_id')->setValueOptions($tmpArrayCategories);

        $request = $this->getRequest();

        if ($request->isPost())
        {

            $form->setInputFilter(new ProductFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $productId = $this->getProductTable()->save($product);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $fileSize = filesize($this->uploadDir.'/'.$file);
                        $mimeType = $this->getMimeTypeFromPath($this->uploadDir.'/'.$file);

                        $productFile = new ProductFile();
                        $productFile->setFilename($file);
                        $productFile->setProductId($productId);
                        $productFile->setSize($fileSize);
                        $productFile->setMimeType($mimeType);

                        $this->getProductFileTable()->save($productFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->flashMessenger()->addMessage('Produkt został zedytowany poprawnie.');

                return $this->redirect()->toRoute('realization/product', array('realization_id' => $realizationId));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['productFiles'] = $productFiles;
        $viewParams['realizationId'] = $realizationId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteProductAction()
    {
        $request = $this->getRequest();

        $realizationId = $this->params()->fromRoute('realization_id');
        $id = (int) $this->params()->fromRoute('product_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('realization/product', array('realization_id' => $realizationId));
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $productFiles = $this->getProductFileTable()->getBy(array('product_id' => $id));
                if(!empty($productFiles))
                {
                    foreach($productFiles as $file)
                    {
                        $filename = $file->getFilename();
                        $pathToFile = $this->destinationUploadDir.$filename;
                        unlink($pathToFile);

                        $this->getProductFileTable()->deleteProductFile($file->getId());
                    }
                }

                $this->getProductTable()->deleteProduct($id);
                $this->flashMessenger()->addMessage('Produkt został usunięty poprawnie.');


                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('realization/product', array('realization_id' => $realizationId));
        }

        return array(
            'id'    => $id,
            'page'  => $this->getProductTable()->getOneBy(array('id' => $id))
        );
    }

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $filePath = $request->getPost('filePath');

            if($id != 0)
            {
                $this->getProductFileTable()->deleteProductFile($id);
                unlink('./public'.$filePath);
            } else
            {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    public function uploadGalleryAction ()
    {
        if (!empty($_FILES)) {
            //var_dump($_FILES);die;
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

    public function uploadMainAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->destinationUploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
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

    /**
     * @return \CmsIr\Product\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Product\Model\CategoryTable');
    }

    private function getMimeTypeFromPath($file) {
        // MIME types array
        $mimeTypes = array(
            "323"       => "text/h323",
            "acx"       => "application/internet-property-stream",
            "ai"        => "application/postscript",
            "aif"       => "audio/x-aiff",
            "aifc"      => "audio/x-aiff",
            "aiff"      => "audio/x-aiff",
            "asf"       => "video/x-ms-asf",
            "asr"       => "video/x-ms-asf",
            "asx"       => "video/x-ms-asf",
            "au"        => "audio/basic",
            "avi"       => "video/x-msvideo",
            "axs"       => "application/olescript",
            "bas"       => "text/plain",
            "bcpio"     => "application/x-bcpio",
            "bin"       => "application/octet-stream",
            "bmp"       => "image/bmp",
            "c"         => "text/plain",
            "cat"       => "application/vnd.ms-pkiseccat",
            "cdf"       => "application/x-cdf",
            "cer"       => "application/x-x509-ca-cert",
            "class"     => "application/octet-stream",
            "clp"       => "application/x-msclip",
            "cmx"       => "image/x-cmx",
            "cod"       => "image/cis-cod",
            "cpio"      => "application/x-cpio",
            "crd"       => "application/x-mscardfile",
            "crl"       => "application/pkix-crl",
            "crt"       => "application/x-x509-ca-cert",
            "csh"       => "application/x-csh",
            "css"       => "text/css",
            "dcr"       => "application/x-director",
            "der"       => "application/x-x509-ca-cert",
            "dir"       => "application/x-director",
            "dll"       => "application/x-msdownload",
            "dms"       => "application/octet-stream",
            "doc"       => "application/msword",
            "dot"       => "application/msword",
            "dvi"       => "application/x-dvi",
            "dxr"       => "application/x-director",
            "eps"       => "application/postscript",
            "etx"       => "text/x-setext",
            "evy"       => "application/envoy",
            "exe"       => "application/octet-stream",
            "fif"       => "application/fractals",
            "flr"       => "x-world/x-vrml",
            "gif"       => "image/gif",
            "gtar"      => "application/x-gtar",
            "gz"        => "application/x-gzip",
            "h"         => "text/plain",
            "hdf"       => "application/x-hdf",
            "hlp"       => "application/winhlp",
            "hqx"       => "application/mac-binhex40",
            "hta"       => "application/hta",
            "htc"       => "text/x-component",
            "htm"       => "text/html",
            "html"      => "text/html",
            "htt"       => "text/webviewhtml",
            "ico"       => "image/x-icon",
            "ief"       => "image/ief",
            "iii"       => "application/x-iphone",
            "ins"       => "application/x-internet-signup",
            "isp"       => "application/x-internet-signup",
            "jfif"      => "image/pipeg",
            "jpe"       => "image/jpeg",
            "jpeg"      => "image/jpeg",
            "jpg"       => "image/jpeg",
            "js"        => "application/x-javascript",
            "latex"     => "application/x-latex",
            "lha"       => "application/octet-stream",
            "lsf"       => "video/x-la-asf",
            "lsx"       => "video/x-la-asf",
            "lzh"       => "application/octet-stream",
            "m13"       => "application/x-msmediaview",
            "m14"       => "application/x-msmediaview",
            "m3u"       => "audio/x-mpegurl",
            "man"       => "application/x-troff-man",
            "mdb"       => "application/x-msaccess",
            "me"        => "application/x-troff-me",
            "mht"       => "message/rfc822",
            "mhtml"     => "message/rfc822",
            "mid"       => "audio/mid",
            "mny"       => "application/x-msmoney",
            "mov"       => "video/quicktime",
            "movie"     => "video/x-sgi-movie",
            "mp2"       => "video/mpeg",
            "mp3"       => "audio/mpeg",
            "mpa"       => "video/mpeg",
            "mpe"       => "video/mpeg",
            "mpeg"      => "video/mpeg",
            "mpg"       => "video/mpeg",
            "mpp"       => "application/vnd.ms-project",
            "mpv2"      => "video/mpeg",
            "ms"        => "application/x-troff-ms",
            "mvb"       => "application/x-msmediaview",
            "nws"       => "message/rfc822",
            "oda"       => "application/oda",
            "p10"       => "application/pkcs10",
            "p12"       => "application/x-pkcs12",
            "p7b"       => "application/x-pkcs7-certificates",
            "p7c"       => "application/x-pkcs7-mime",
            "p7m"       => "application/x-pkcs7-mime",
            "p7r"       => "application/x-pkcs7-certreqresp",
            "p7s"       => "application/x-pkcs7-signature",
            "pbm"       => "image/x-portable-bitmap",
            "pdf"       => "application/pdf",
            "pfx"       => "application/x-pkcs12",
            "pgm"       => "image/x-portable-graymap",
            "pko"       => "application/ynd.ms-pkipko",
            "pma"       => "application/x-perfmon",
            "pmc"       => "application/x-perfmon",
            "pml"       => "application/x-perfmon",
            "pmr"       => "application/x-perfmon",
            "pmw"       => "application/x-perfmon",
            "pnm"       => "image/x-portable-anymap",
            "pot"       => "application/vnd.ms-powerpoint",
            "ppm"       => "image/x-portable-pixmap",
            "pps"       => "application/vnd.ms-powerpoint",
            "ppt"       => "application/vnd.ms-powerpoint",
            "prf"       => "application/pics-rules",
            "ps"        => "application/postscript",
            "pub"       => "application/x-mspublisher",
            "qt"        => "video/quicktime",
            "ra"        => "audio/x-pn-realaudio",
            "ram"       => "audio/x-pn-realaudio",
            "ras"       => "image/x-cmu-raster",
            "rgb"       => "image/x-rgb",
            "rmi"       => "audio/mid",
            "roff"      => "application/x-troff",
            "rtf"       => "application/rtf",
            "rtx"       => "text/richtext",
            "scd"       => "application/x-msschedule",
            "sct"       => "text/scriptlet",
            "setpay"    => "application/set-payment-initiation",
            "setreg"    => "application/set-registration-initiation",
            "sh"        => "application/x-sh",
            "shar"      => "application/x-shar",
            "sit"       => "application/x-stuffit",
            "snd"       => "audio/basic",
            "spc"       => "application/x-pkcs7-certificates",
            "spl"       => "application/futuresplash",
            "src"       => "application/x-wais-source",
            "sst"       => "application/vnd.ms-pkicertstore",
            "stl"       => "application/vnd.ms-pkistl",
            "stm"       => "text/html",
            "svg"       => "image/svg+xml",
            "sv4cpio"   => "application/x-sv4cpio",
            "sv4crc"    => "application/x-sv4crc",
            "t"         => "application/x-troff",
            "tar"       => "application/x-tar",
            "tcl"       => "application/x-tcl",
            "tex"       => "application/x-tex",
            "texi"      => "application/x-texinfo",
            "texinfo"   => "application/x-texinfo",
            "tgz"       => "application/x-compressed",
            "tif"       => "image/tiff",
            "tiff"      => "image/tiff",
            "tr"        => "application/x-troff",
            "trm"       => "application/x-msterminal",
            "tsv"       => "text/tab-separated-values",
            "txt"       => "text/plain",
            "uls"       => "text/iuls",
            "ustar"     => "application/x-ustar",
            "vcf"       => "text/x-vcard",
            "vrml"      => "x-world/x-vrml",
            "wav"       => "audio/x-wav",
            "wcm"       => "application/vnd.ms-works",
            "wdb"       => "application/vnd.ms-works",
            "wks"       => "application/vnd.ms-works",
            "wmf"       => "application/x-msmetafile",
            "wps"       => "application/vnd.ms-works",
            "wri"       => "application/x-mswrite",
            "wrl"       => "x-world/x-vrml",
            "wrz"       => "x-world/x-vrml",
            "xaf"       => "x-world/x-vrml",
            "xbm"       => "image/x-xbitmap",
            "xla"       => "application/vnd.ms-excel",
            "xlc"       => "application/vnd.ms-excel",
            "xlm"       => "application/vnd.ms-excel",
            "xls"       => "application/vnd.ms-excel",
            "xlsx"      => "vnd.ms-excel",
            "xlt"       => "application/vnd.ms-excel",
            "xlw"       => "application/vnd.ms-excel",
            "xof"       => "x-world/x-vrml",
            "xpm"       => "image/x-xpixmap",
            "xwd"       => "image/x-xwindowdump",
            "z"         => "application/x-compress",
            "zip"       => "application/zip"
        );
        $fileInfo = explode('.', $file);
        $extension = end($fileInfo);
        return $mimeTypes[$extension]; // return the array value
    }
}