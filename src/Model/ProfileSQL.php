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

        $sql = "INSERT INTO User (username, email, password, name, birthdate, phone, image_dir) VALUES (?,?,?,?,?,?,?)";

        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = MD5($user->getPassword());
        $name = $user->getName();
        $birthdate = $user->getBirthdate();
        $phone = $user->getPhone();
        $image_dir = $user->getImageDir();
        $db->prepare($sql)->execute([$username,$email,$password, $name, $birthdate, $phone,$image_dir]);
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
                WHERE password LIKE MD5(" . ":password" . ") AND isActive = TRUE ";

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
            $sql = "SELECT username, email, isActive FROM User WHERE ? LIKE ";
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

    public function getUserDetails(string $id){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM User
                WHERE email LIKE " . ":id" . " ";


        // select a particular user by id
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
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

    public function getUserbyId(string $id){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM User
                WHERE id LIKE " . ":id" . " ";


        // select a particular user by id
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
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

    public function getAllProducts(){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $stmt = null;
        if (isset($_SESSION['profile']['id'])){
            $sql = "SELECT * FROM Product WHERE 
                id NOT IN (SELECT product FROM UserProductOwn WHERE owner LIKE ?)
                AND isActive = true
            ORDER BY id DESC 
            LIMIT 5";
            $stmt = $db->prepare($sql);
            $stmt->execute([$_SESSION['profile']['id']]);

        } else {
            $sql = "SELECT * FROM Product WHERE isActive = 1 ORDER BY id DESC LIMIT 5";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }

        $products  = $stmt->fetchAll();
        return $products;
    }

    public function getProductsSearch(string $nameProduct){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT * FROM Product WHERE title LIKE '$nameProduct' LIMIT 5";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $productsSearch  = $stmt->fetchAll();
        return $productsSearch;
    }


    public function isLike(int $idProducte ,string $idUser){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT COUNT(*) FROM Favorites WHERE Favorites.product LIKE $idProducte AND Favorites.user LIKE $idUser";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count  = $stmt->fetchAll();
        return $count;
    }

    public function deleteLike(int $idProducte ,string $idUser) {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "DELETE FROM Favorites WHERE Favorites.user='$idUser' AND product = $idProducte";

        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public function addLike(int $idProducte ,string $idUser) {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "INSERT INTO Favorites(user,product)VALUES ('$idUser',$idProducte)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public function getProductById(int $idProduct){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "SELECT * FROM Product WHERE id LIKE $idProduct";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $productsId  = $stmt->fetchAll();
        return $productsId;
    }


    public function deleteAccount(string $id){
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $stmt = $db->prepare('SELECT isActive FROM User WHERE email LIKE "'.$id.'"');
        $stmt->execute();
        $row = $stmt->fetch();

        if($row['isActive'] == 1){
            $sql = "UPDATE `PWPOP`.`User` t SET t.`isActive` = 0 WHERE t.`email` LIKE '" . $id . "' ESCAPE '#'";
            $stmt = $db->prepare($sql);
            $done = $stmt->execute();

            return $done;
        } else {
            return "Error! How did you get here?";
        }
    }
}