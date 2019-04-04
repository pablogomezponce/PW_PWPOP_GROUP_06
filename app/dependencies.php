<?php

$container = [];

$container['renderer'] = new \SallePW\View\Renderer();
$container['service'] = new \SallePW\Model\Services\PostTaskService(
    new \SallePW\Model\MySQLTaskRepository()
);

return $container;
