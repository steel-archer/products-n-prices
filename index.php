<?php

require __DIR__ . '/vendor/autoload.php';

$action = $_REQUEST['action'];

switch ($action) {
    case 'find':
        $controller = new \PNP\Controllers\ProductController($_REQUEST);
        $controller->find();
        break;
    case 'save':
        $controller = new \PNP\Controllers\ProductController($_REQUEST);
        $controller->save();
        break;
    default:
        $controller = new \PNP\Controllers\BasicController();
        $controller->error404();
        break;
}
