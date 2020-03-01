<?php

require __DIR__ . '/vendor/autoload.php';

$action = 'save'; // There should be an action
$request  = [
    'code'  => 'Test product',
    'attrs' => [
        'description' => 'product description',
        'normal_price_override' => false,
        'normal_price' => [
            'GBP' => 10.00,
            'USD' => 20.00,
            'CAD' => 30.00,
        ],
        'special_price_override' => true,
        'special_price' => [
            'GBP' => 1.00,
            'USD' => 2.00,
            'CAD' => 3.00,
        ],
    ],
]; // There should be attrs
$request['attrs'] = trimArray($request['attrs']);

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
