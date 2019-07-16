<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\ProfileRepository;
use SallePW\Model\ProfileSQL;
use SallePW\Model\User;

class ProfileController
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
     * @param ProfileRepository $MySQLService
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Check if user status is valid
     * @param User $user
     * @return array
     */
    private function checkUser(User $user){
        $attr = ($user->getAttributes());

        $len = 0;
        $errors = [];

        if ($_POST['password'] != $_POST['passwordValidation']) $errors['pass2'] = "Passwords don't match";
        if (strlen($_POST['password']) < 6)  $errors['pass'] = "This password is VERY SHORT";
        if (preg_match( "/\W/",($_POST['name']))) $errors['name'] = "Do you need those characters? We just want alphanumberic";
        //if(strlen($_POST['username']) > 20 || preg_match("/[[:alnum:]]/", $_POST['username'])) $errors['username'] = "This username is way 2 long and/or has illegal chars!";

        $phone = (filter_var($user->getPhone(), FILTER_SANITIZE_NUMBER_INT));

        $phone = str_replace("-", "", $phone);

        if (strlen($phone) != 9 || preg_match('/([[:alpha:]])/', $user->getPhone())) {$errors['phone'] = "This is an invalid phone number";} else { $user->setPhone($phone);}

        if( strtotime($_POST['bday']) > strtotime('now') ) {
            $errors['bday'] = "Are you XS? 'Cause you come from the future ewe";
        }

        return $errors;
    }


    /**
     * GET /profile
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {

        $messages = $this->container->get('flash')->getMessages();
        if(!isset($_SESSION['profile'])){
            $response = $response->withStatus(403);
            return $this->container->get('view')->render($response, 'error403.twig', [
                'title' => 'PWPop | ERROR',
                'content' => 'Error, you should log in first!',
                'footer' => '',
                'action' => 'updateProfile',
            ]);
        } else {
            $exists  = $this->container->get('profileSQL')->getUserDetails($_SESSION['profile']['email'])[0];
            $products = $this->container->get('productSQL')->getAllProductsBy($_SESSION['profile']['id']);
            $htmlProd = [];

            foreach ($products as $product)
            {
                array_push($htmlProd, $this->container->get('productSQL')->get($product['product']));
            }

            $htmlProdReturned = [];

            foreach ($htmlProd as $prod){
                $prod['owner'] = $_SESSION['profile']['email'];
                array_push($htmlProdReturned, $prod);
            }


            return $this->container->get('view')->render($response, 'profile.twig', [
                'title' => 'PWPop | USER',
                'content' => 'Laura Gendrau i Pablo Gómez',
                'footer' => '',
                'sessionStarted' => $exists['username'],
                'user' => $exists,
                'username' => $exists['username'],
                'email' => $exists['email'],
                'name' => $exists['name'],
                'phone'=>$exists['phone'],
                'birthday' => $exists['birthdate'],
                'idUser' => $exists['email'],
                'products' => $htmlProdReturned,
                'action' => 'updateProfile',
                'messages'=> $messages,

            ]);
        }
    }

    /**
     * POST /profile
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function updateProfile(Request $request, Response $response, array $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $errors = [];
        $name = null;

        $user = new User(null,$_POST['name'],"", $_POST['email'], $_POST['username'], $_POST['password'], $_POST['phone'], $_POST['bday'],$name);

        $status = $this->checkUser($user);


        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile->getSize() < 500 * 1024) {

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
                // We generate a custom name here instead of using the one coming form the form
                $uploadedFile->moveTo(self::UPLOADS_DIR . "/".$_POST['username'] . "/" . $name);
                $user->setImageDir($_POST['username']."/".$name);
            } else {
                $status['file'] = "That's a huge file for us, please make it smaller";
            }
        }

        if (empty($status)){
            $val = $this->container->get('profileSQL')->update($user);
            $this->container->get('flash')->addMessage('test', 'Updated!');
            return $response->withHeader('location', '/profile');
        } else {
            return $this->container->get('view')->render($response, 'SignUp.twig',[
                'title' => 'PWPop | Sign up',
                'content' => 'Laura Gendrau i Pablo Gómez',
                'footer' => '',
                'sessionStarted' => null,
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'username' => $_POST['username'],
                'phone'=>$_POST['phone'],
                'birthday' => $_POST['bday'],
                'error' => $status,

            ]);
        }
    }

    /**
     * Checks that the given extension is valid
     * @param string $extension
     * @return bool
     */
    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }


}



