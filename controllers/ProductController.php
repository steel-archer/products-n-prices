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
     */
    public function find(): void
    {
        if (!isset($this->request['submit'])) {
            $params = [];
        } else {
            $this->checkCsrfToken();
            $code   = trim($this->request['code']);
            $result = (new Product(new ProductMapper()))->find($code);
            if (!empty($result['product'])) {
                $result['product'] = json_encode($result['product'], JSON_PRESERVE_ZERO_FRACTION);
            }
            $params = [
                'code'    => $code,
                'product' => $result['product'] ?? [],
                'errors'  => $result['errors'] ?? [],
            ];
        }

        $this->render('product/find', $params);
    }

    /**
     * Saves a product
     */
    public function save(): void
    {
        $product = new Product(new ProductMapper());
        if (!isset($this->request['submit'])) {
            $params = [];
        } else {
            $this->checkCsrfToken();
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
