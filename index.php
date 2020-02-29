<?php

require __DIR__ . '/vendor/autoload.php';

$action = 'find'; // There should be an action
$attrs  = []; // There should be attrs

$controller = new \PNP\Controllers\ProductController();

switch ($action) {
    case 'find':
        $controller->find($attrs);
        break;
    case 'save':
        $controller->save($attrs);
        break;
    default:
        throw new \DomainException("Unknown action '{$action}'");
        break;
}
