<?php

namespace PNP\Controllers;

use PNP\Components\DbEntities\ProductMapper;
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
        $result = (new Product(new ProductMapper()))->find($code);
        $this->render(
            'find',
            [
                'code'   => $code,
                'result' => json_encode($result, JSON_PRESERVE_ZERO_FRACTION),
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
        $errors = (new Product(new ProductMapper()))->save($code, $attrs);

        $this->render(
            'save',
            [
                'errors' => $errors,
            ]
        );
    }
}
