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
        $errors = [];
        $name = null;

        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                continue;
            }

            $name = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];

            if (!$this->isValidFormat($format)) {
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                continue;
            }
            var_dump(self::UPLOADS_DIR . "/" . $_SESSION['profile']['username']);

            //mkdir(self::UPLOADS_DIR . "/" . $_SESSION['profile']['username'] . "/");
            // We generate a custom name here instead of using the one coming form the form
            $uploadedFile->moveTo(self::UPLOADS_DIR . "/". $_SESSION['profile']['username'] . "/" . "article_" . $name);
        }

        $product = new Product($_POST['title'], $_POST['description'], $_POST['price'], $name, $_POST['category'], true);

        $id = $this->container->get('productSQL')->save($product, $_SESSION['profile']['id']);

        $this->container->get('productSQL')->associate($id, $_SESSION['profile']['id']);

        return $response->withHeader("Location", "/profile");
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

}