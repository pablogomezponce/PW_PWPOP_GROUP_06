<?php


namespace SallePW\Model;


interface ProductRepository
{
    public function save(Product $product);
    public function get(int $id);
}