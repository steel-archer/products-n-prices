<?php

require __DIR__ . '/vendor/autoload.php';

$action = $_REQUEST['action'];

switch ($action) {
    case 'find':
        $controller = new \PNP\Controllers\ProductController();
        $controller->find($_REQUEST);
        break;
    case 'save':
        $controller = new \PNP\Controllers\ProductController();
        //$controller->save($code, []);
        break;
    default:
        $controller = new \PNP\Controllers\BasicController();
        $controller->error404();
        break;
}
