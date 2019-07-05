<?php


namespace SallePW\Controller;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SallePW\Model\Product;

class UploadProduct
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

    public function __invoke(Request $request, Response $response, array $args)
    {
        return $this->container->get('view')->render($response, 'upload.twig', [
            'title' => 'PWPop | Post a product!',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'info' => $_POST,
            'footer' => '',
            'profile' => $_SESSION['profile'],
            'sessionStarted' =>$_SESSION['sessionStarted'],
            'idUser' => $_SESSION['idUser'],
        ]);
    }

    public function post(Request $request, Response $response, array $args){
        $uploadedFiles = $request->getUploadedFiles();

        $product = new Product($_POST['title'], $_POST['description'], $_POST['price'], null, $_POST['category'], true);

        $errors = $this->checkProduct($product);


        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile->getSize() < (1024*1024)) {
                if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                    $errors[] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                    continue;
                }

                $name = $uploadedFile->getClientFilename();

                $fileInfo = pathinfo($name);

                $format = $fileInfo['extension'];

                $product->setProductImageDir($name);
                if (!$this->isValidFormat($format)) {
                    $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                    continue;
                }
            } else {
                $errors['file'] = 1;
            }

        }


        if (!empty($errors)){
            var_dump($errors);
            return $this->container->get('view')->render($response, 'upload.twig', [
                'title' => 'PWPop | Post a product!',
                'content' => 'Laura Gendrau i Pablo Gómez',
                'info' => $_POST,
                'footer' => '',
                'profile' => $_SESSION['profile'],
                'sessionStarted' =>$_SESSION['sessionStarted'],
                'idUser' => $_SESSION['idUser'],
                'error' => $errors,
            ]);
        }


        $id = $this->container->get('productSQL')->save($product, $_SESSION['profile']['id']);

        $this->container->get('productSQL')->associate($id, $_SESSION['profile']['id']);


        foreach ($uploadedFiles as $uploadedFile)
        {
            mkdir(self::UPLOADS_DIR . "/products" . "/$id");
            $uploadedFile->moveTo(self::UPLOADS_DIR . "/products/" . $id . "/$name");
        }

        return $response->withHeader("Location", "/profile");
    }

    private function checkProduct(Product $product) : array
    {
        $error = [];
        if (strlen($product->getDescription()) < 20) $error['description'] = 1;
        if (strlen($product->getTitle()) == 0) $error['title'] = 1;
        if (!is_numeric($product->getPrice())) $error['price'] = 1;
        if (!in_array($product->getCategory(), self::CATEGORIES, true)) $error['categories'] = 1;

        return $error;
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

}