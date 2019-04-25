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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function  addToDB(Request $request, Response $response, array $args){
        $user = new User(null,$_POST['name'], $_POST['lastname'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['phone'], $_POST['bday'],null);

        $status = $this->checkUser($user);

        if (empty($status)){
            $this->container->get('profileSQL')->save($user);
            //header('Location: /profile' );
        } else {
            return $this->container->get('view')->render($response, 'SignUp.twig',[
                'title' => 'PWPop | Sign up',
                'content' => 'Laura Gendrau i Pablo Gómez',
                'footer' => '',
                'sessionStarted' => null,
                'formName' => $_POST['name'],
                'formLastName' => $_POST['lastname'],
                'formEmail' => $_POST['email'],
                'formUsername' => $_POST['username'],
                'formPhone'=>$_POST['phone'],
                'formbday' => $_POST['bday'],
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


