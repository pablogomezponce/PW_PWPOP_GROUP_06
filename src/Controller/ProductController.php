<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\Product;

class ProductController
{
    /** @var ContainerInterface */
    private $container;


    private const UPLOADS_DIR = __DIR__ . '/../../public/uploads';
    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";
    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";
    private const ALLOWED_EXTENSIONS = ['jpg', 'png'];
    private const CATEGORIES = ["Computers", "Cars", "Sports","Games","Fashion","Home","Other"];

    /**
     * HelloController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * GET product overview
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {

        $ownStatus = $this->isOwner($_GET['idProducte']);

        $product = $this->getProductById($_GET['idProducte']);
        $params = [
            'title' => 'PWPop | Product',
            'footer' => '',
            'product' => $product,
        ];
        if(!empty($_SESSION['profile']))
        {
            $params['idUser'] = $_SESSION['profile']['id'];
            $params['username']= $_SESSION['profile']['username'];
        }


        if ($ownStatus)
        {
            return $this->container->get('view')->render($response, 'modifyProduct.twig', $params);
        }
        else
        {
            return $this->container->get('view')->render($response, 'productDetails.twig', $params);
        }
    }

    /**
     * Check Product conditions
     * @param Product $product
     * @return array
     */
    private function checkProduct(Product $product) : array
    {
        $error = [];
        if (strlen($product->getDescription()) < 20) $error['description'] = 1;
        if (strlen($product->getTitle()) == 0) $error['title'] = 1;
        if (!is_numeric($product->getPrice())) $error['price'] = 1;
        if (!in_array($product->getCategory(), self::CATEGORIES, true)) $error['categories'] = 1;

        return $error;
    }


    /**
     * Check if image is valid
     * @param string $extension
     * @return bool
     */
    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }


    /**
     * POST /update
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function updateProduct(Request $request, Response $response, array $args)
    {
        $prod = new Product($_POST['title'], $_POST['description'], $_POST['price'], null, $_POST['category'], 1);
        $prod->setId($_POST['submit']);

        //Gestion de producto
        $err = $this->checkProduct($prod);

        //files
        $uploadedFiles = $request->getUploadedFiles();


        if (count($uploadedFiles) > 1)
        {
            $err['file'] = "We need just 1 picture";
        }
        else if (empty($err))
        {
            var_dump($prod);

            //Check file status
            foreach ($uploadedFiles as $uploadedFile) {

                //file size
                if ($uploadedFile->getSize() < (1024*1024)) {

                    //Error @ upload
                    if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                        $err['getError'] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                        continue;
                    }

                    $name = $uploadedFile->getClientFilename();

                    $fileInfo = pathinfo($name);

                    $format = $fileInfo['extension'];

                    $prod->setProductImageDir($name);
                    if (!$this->isValidFormat($format)) {
                        $err[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                        continue;
                    }

                    $uploadedFile->moveTo(self::UPLOADS_DIR . "/products/" . $_POST['submit'] . "/$name");
                } else {
                    $err['file'] = 1;
                }
            }

            $this->container->get('productSQL')->updateProduct($prod);
            return $response->withHeader('location', '/product?idProducte=' . $_POST['submit']);

        }

        $product = $this->getProductById($_POST['submit']);
        $params = [
            'title' => 'PWPop | Product',
            'footer' => '',
            'product' => $product,
        ];
        if(!empty($_SESSION['profile']))
        {
            $params['idUser'] = $_SESSION['profile']['id'];
            $params['username']= $_SESSION['profile']['username'];
        }

        $params['error'] = $err;
        $params['product']=$product;

        var_dump($params);
        return $this->container->get('view')->render($response, 'modifyProduct.twig', $params);









    }


    /**
     * Get Product based on ID
     * @param $prodId
     * @return mixed
     */
    public function getProductById($prodId){
        $product = $this->container->get('profileSQL')->getProductById($prodId);
        return $product;
    }

    /**
     * Check if user likes a product
     * @return false|string
     */
    public function isLike(){
        $idLike = $this->container->get('profileSQL')->isLike($_GET['idProducte'],3);
        if ($idLike[0][0] > 0){
            $idLike = true;
            //delete
            $this->container->get('profileSQL')->deleteLike($_GET['idProducte'],3);
        }else{
            $idLike = false;
            //add
            $this->container->get('profileSQL')->addLike($_GET['idProducte'],3);
        }
        return json_encode(array($idLike,$_GET['idProducte'],'holi'));
    }

    /**
     * Ask for ownership
     * @param $prodID
     * @return bool
     */
    public function isOwner($prodID){
        if (!empty($_SESSION['profile']['id']))
        {
            return $this->container->get('productSQL')->isOwner($prodID,$_SESSION['profile']['id']);
        } else {
            return false;
        }
    }

    /**
     * POST /delete
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteProduct(Request $request, Response $response, array $args)
    {
        $this->container->get('productSQL')->removeProduct($_POST['productID']);

        return $response->withHeader('location', '/myproducts');
    }


}