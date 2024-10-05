<?php
/**
 *  REST Router Class
 * @name Router.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Common;

use OpenPOS\Controllers\BaseController;

/**
 * REST Router Class
 */
class Router
{
    /**
     * List of Routes and their Controllers
     * @var array
     */
    protected array $routes = [];

    /**
     * @param string $route
     * @param BaseController $controller
     * @return void
     */
    public function RegisterRoute(string $route, \OpenPOS\Controllers\BaseController $controller): void
    {
        $this->routes[$route] = $controller;
    }

    public function Route($uri)
    {
        $this->routes[$uri]->{strtolower($_SERVER['REQUEST_METHOD'])}($uri);
    }

}