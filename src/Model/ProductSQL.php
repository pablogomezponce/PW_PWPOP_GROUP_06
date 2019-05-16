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

        $db->prepare($sql)->execute([$product->getTitle(),$product->getDescription(),$product->getPrice(),$product->getProductImageDir(),$product->getCategory(),true]);


    }

    public function get(int $id)
    {
        // TODO: Implement get() method.
    }
}