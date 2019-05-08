<?php

namespace SallePW\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\Model\User;

class signUpController
{
    /** @var ContainerInterface */
    private $container;

    private const UPLOADS_DIR = __DIR__ . '/../../uploads';
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
        return $this->container->get('view')->render($response, 'SignUp.twig', [
            'title' => 'PWPop | Sign up',
            'content' => 'Laura Gendrau i Pablo Gómez',
            'footer' => '',
            'sessionStarted' => null,
        ]);
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function  addToDB(Request $request, Response $response, array $args){
        $user = new User(null,$_POST['name'],"", $_POST['email'], $_POST['username'], $_POST['password'], $_POST['phone'], $_POST['bday'],null);

        $uploadedFiles = $request->getUploadedFiles();
        var_dump($uploadedFiles);
        $errors = [];

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

            // We generate a custom name here instead of using the one coming form the form
            $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $name);
        }


        $status = $this->checkUser($user);

        if (empty($status)){
            $this->container->get('profileSQL')->save($user);
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

    private function checkUser(User $user){
        $attr = ($user->getAttributes());

        $len = 0;
        $errors = [];

        if ($_POST['password'] != $_POST['passwordValidation']) $errors['password'] = "Passwords don't match";

        if(!empty($this->container->get('profileSQL')->checkIfEmailExists($_POST['email']))) $errors['email'] = "This email already exists!";
        if(!empty($this->container->get('profileSQL')->checkIfUsernameExists($_POST['username']))) $errors['username'] = "This username already exists!";

        $phone = (filter_var($user->getPhone(), FILTER_SANITIZE_NUMBER_INT));

        $phone = str_replace("-", "", $phone);

        if (strlen($phone) != 9 || preg_match('/([[:alpha:]])/', $user->getPhone())) {$errors['phone'] = "This is an invalid phone number";} else { $user->setPhone($phone);}

        return $errors;
    }
}


