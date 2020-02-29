<?php

namespace PNP\Controllers;

use PNP\Components\Product;

/**
 * Class ProductController
 * @package PNP\Controllers
 */
class ProductController extends Controller
{
    /**
     * Finds a product
     * @param string $code
     * @throws \Exception
     */
    public function find(string $code): void
    {
        $result = (new Product())->find($code);
        $this->render(
            'find',
            [
                'code'   => $code,
                'result' => json_encode($result),
            ]
        );
    }

    /**
     * Saves a product
     * @param string $code
     * @param array $attrs
     * @throws \Exception
     */
    public function save(string $code, array $attrs): void
    {
        $this->render(
            'save',
            [
                'code'  => $code,
                'attrs' => $attrs,
            ]
        );
    }
}
