<?php

namespace PNP\Controllers;

use PHPUnit\Exception;

/**
 * Class Controller
 * @package PNP\Controllers
 */
abstract class Controller
{
    /**
     * @var array
     */
    protected $request;

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param array $request
     * @return Controller
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Controller constructor.
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    /**
     * Renders a html view
     * @param string $view
     * @param array $attrs
     * @throws \Exception
     */
    public function render(string $view, array $attrs): void
    {
        $attrs['csrfToken'] = $_SESSION['csrfToken'];

        try {
            static $twig = null;
            if ($twig === null) {
                $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
                $twig   = new \Twig\Environment($loader);
            }
            echo $twig->render("{$view}.twig", $attrs);
        } catch (\Exception $ex) {
            echo 'Twig exception';
            exit(1);
        }
    }

    /**
     * Checks CSRF token
     */
    public function checkCsrfToken(): void
    {
        if (!hash_equals($_SESSION['csrfToken'], $this->request['csrfToken'])) {
            echo "Invalid CSRF token";
            exit(1);
        }
    }
}
