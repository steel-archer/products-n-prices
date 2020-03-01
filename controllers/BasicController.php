<?php

namespace PNP\Controllers;

/**
 * Class BasicController
 * @package PNP\Controllers
 */
class BasicController extends Controller
{
    /**
     * Renders page for error 404
     */
    public function error404() : void
    {
        $this->render('basic/error404', []);
    }
}
