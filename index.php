<?php

require __DIR__ . '/vendor/autoload.php';

$action = 'find'; // There should be an action
$request  = [
    'code'  => 'Test product',
    'attrs' => [

    ]
]; // There should be attrs

$controller = new \PNP\Controllers\ProductController();

switch ($action) {
    case 'find':
        $controller->find($request['code']);
        break;
    case 'save':
        $controller->save($request['code'], $request['attrs']);
        break;
    default:
        throw new \DomainException("Unknown action '{$action}'");
        break;
}
