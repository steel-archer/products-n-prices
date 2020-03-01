<?php

require __DIR__ . '/vendor/autoload.php';

$action  = $_REQUEST['action'];
$code    = trim($_REQUEST['code']);
$request = [];

$controller = new \PNP\Controllers\ProductController();

switch ($action) {
    case 'find':
        if (!empty($code)) {
            $request['code'] = $code;
        }
        $controller->find($request);
        break;
    case 'save':
        $controller->save($code, []);
        break;
    default:
        //throw new \DomainException("Unknown action '{$action}'");
        break;
}
