<?php

namespace PNP\Controllers;

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
     */
    public function render(string $view, array $attrs): void
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render("{$view}.twig", $attrs);
    }
}
