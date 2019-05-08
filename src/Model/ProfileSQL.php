<?php


namespace SallePW\Model;


use PDO;
use PDOException;
use function PHPSTORM_META\elementType;
use Psr\Container\ContainerInterface;

class ProfileSQL implements ProfileRepository
{
    private $address;
    private $dbname;
    private $userNameDB;
    private $passwordDB;

    /**
     * ProfileSQL constructor.
     */
    public function __construct($settings)
    {
        $this->address = $settings['address'];
        $this->dbname = $settings['dbname'];
        $this->userNameDB = $settings['userNameDB'];
        $this->passwordDB = $settings['passwordDB'];

    }

    public function save(User $user)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "INSERT INTO User (username, email, password, name, birthdate, phone) VALUES (?,?,?,?,?,?)";

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = /*md5*/($user->getPassword());
        $name = $user->getPassword();
        $birthdate = $user->getBirthdate();
        $phone = $user->getPhone();
        $db->prepare($sql)->execute([$username,$email,$password, $name, $birthdate, $phone]);
    }

    public function get(array $fields, string $table, string $conditions)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $elements = "";

        foreach ($fields as $field) {
            $elements = $elements . $field . ",";
        }

        $elements = rtrim($elements,',');

        var_dump($elements);
        var_dump($table);
        var_dump($conditions);

        $sql = "SELECT ? FROM User";

        $stmt = $db->prepare($sql);
        var_dump($stmt);
        $stmt->execute([$elements]);
        $variables  = $stmt->fetchAll();
        var_dump($variables);
        return $variables;
    }

    public function getEmails(){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT email FROM User";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $variables  = $stmt->fetchAll();
        return $variables;
    }

    public function checkIfEmailExists(string $email){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT email FROM User WHERE email LIKE ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        $variables  = $stmt->fetchAll();
        return $variables;

    }

    public function checkIfUsernameExists(string $username){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT email FROM User WHERE username LIKE ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        $variables  = $stmt->fetchAll();
        return $variables;

    }

    public function login(string $password, string $id)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM User
                WHERE password LIKE " . ":password" . " ";

        if (filter_var($id, FILTER_VALIDATE_EMAIL)){
            $sql = $sql . " AND email LIKE " . ":id" . "";
        } else {
            $sql = $sql . " AND username LIKE " . ":id" . "";
        }

        // select a particular user by id
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id, 'password'=>$password,]);
        $response = $stmt->fetchAll();

        if (sizeof($response) == 0){
            $sql = "SELECT username, email FROM User WHERE ? LIKE ";
            if (filter_var($id, FILTER_VALIDATE_EMAIL)){
                $sql = $sql."email";
            } else {
                $sql =$sql."username";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            $response = $stmt->fetchAll();


            return $response;

        }

        return $response;

    }

    public function getProducts(){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT * FROM Product LIMIT 5";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $products  = $stmt->fetchAll();
        return $products;
    }

}