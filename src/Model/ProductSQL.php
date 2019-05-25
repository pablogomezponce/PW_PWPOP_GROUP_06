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

        return $db->lastInsertId();
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
}