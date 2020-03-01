<?php

namespace PNP\Controllers;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Controller
 * @package PNP\Controllers
 */
abstract class Controller
{
    /**
     * Renders a html view
     * @param string $view
     * @param array $attrs
     * @throws \Exception
     */
    public function render(string $view, array $attrs): void
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
            $twig   = new \Twig\Environment($loader);
        }

        try {
            echo $twig->render("{$view}.twig", $attrs);
        } catch (LoaderError|RuntimeError|SyntaxError $ex) {
            throw new \Exception($ex, "Twig exception");
        }
    }
}
