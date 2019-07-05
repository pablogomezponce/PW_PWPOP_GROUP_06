<?php


namespace SallePW\Model;


use PDO;

class ProductSQL implements ProductRepository
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


    public function save(Product $product)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "INSERT INTO Product (title,description,price,product_image_dir,category,isActive) VALUES (?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$product->getTitle(),$product->getDescription(),$product->getPrice(),$product->getProductImageDir(),$product->getCategory(),true]);

        $id = $db->lastInsertId();
        $name = $id ."/". $product->getProductImageDir();
        var_dump($name);
        $sql = "UPDATE Product SET product_image_dir = '$name' WHERE id = $id";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $id;
    }

    public function getAllProductsBy(int $id)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM UserProductOwn WHERE owner = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $variables  = $stmt->fetchAll();
        return $variables;
    }

    public function get(int $id)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM Product WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $variables  = $stmt->fetchAll();
        return $variables[0];

    }


    public function associate(int $productId, int $userId)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);
        $sql = "INSERT INTO UserProductOwn(owner, product,buyed) VALUES (?,?,false)";

        $stmt = $db->prepare($sql);
        $stmt->execute([$userId, $productId]);

        return var_dump("CA");
    }

    public function getAllProductsByEmail($id)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM User WHERE email LIKE ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        $id = ($stmt->fetch())['id'];


        $sql = "SELECT product FROM UserProductOwn WHERE owner LIKE ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        $owned = $stmt->fetchAll();

        $products = [];

        foreach ($owned as $product)
        {
            $sql = "SELECT * FROM Product WHERE id = ?";
            $stmt = $db->prepare($sql);

            $stmt->execute([$product['product']]);

            array_push($products, $stmt->fetch());
        }

        return $products;
    }

    public function getFavourites($id)
    {
        $db = new PDO('mysql:host=' . $this->address . ';dbname=' . $this->dbname . ';', $this->userNameDB, $this->passwordDB);

        $sql = "SELECT * FROM Product WHERE id IN
                    (SELECT product FROM Favorites WHERE user = ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        var_dump($id);
        return $stmt->fetchAll();
    }
}