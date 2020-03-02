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
     * @throws \Exception
     */
    public function find(): void
    {
        if (empty($this->request['code'])) {
            $params = [];
        } else {
            $code   = trim($this->request['code']);
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
     * @throws \Exception
     */
    public function save(): void
    {
        if (empty($this->request['code']) || empty($this->request['attrs'])) {
            $params = [];
        } else {
            $code  = trim($this->request['code']);
            $attrs = trimArray($this->request['attrs']);
            $params = [
                'errors' => (new Product(new ProductMapper()))->save($code, $attrs),
            ];
        }

        $this->render('product/save', $params);
    }
}
