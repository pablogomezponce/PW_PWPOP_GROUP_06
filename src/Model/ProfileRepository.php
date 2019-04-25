<?php


namespace SallePW\Model;


interface ProfileRepository
{
    public function save(User $user);
    public function get(array $fields, string $table, string $conditions);
    public function login(string $password, string $id);
}