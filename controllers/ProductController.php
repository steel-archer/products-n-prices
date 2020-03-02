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
        if (!isset($this->request['submit'])) {
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
        $product = new Product(new ProductMapper());
        if (!isset($this->request['submit'])) {
            $params = [];
        } else {
            $code  = trim($this->request['code']);
            $attrs = trimArray($this->request['attrs']);
            $params = [
                'code'   => $code,
                'attrs'  => $attrs,
                'errors' => $product->save($code, $attrs),
            ];
        }

        $params['currencies'] = array_keys($product->getCurrencyRates());
        $this->render('product/save', $params);
    }
}
