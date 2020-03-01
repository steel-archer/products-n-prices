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
     * @param array $request
     * @throws \Exception
     */
    public function find(array $request): void
    {
        if (empty($request['code'])) {
            $params = [];
        } else {
            $code   = trim($request['code']);
            $result = (new Product(new ProductMapper()))->find($code);
            $params = [
                'code'   => $code,
                'result' => $result ? json_encode($result, JSON_PRESERVE_ZERO_FRACTION) : [],
            ];
        }

        $this->render('product/find', $params);
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
            'product/save',
            [
                'errors' => $errors,
            ]
        );
    }
}
