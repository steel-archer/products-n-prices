<?php

namespace PNP\Controllers;

/**
 * Class ProductController
 * @package PNP\Controllers
 */
class ProductController extends Controller
{
    public function find(array $attrs): void
    {
        $this->render('find', $attrs);
    }

    public function save(array $attrs): void
    {
        $this->render('save', $attrs);
    }
}
