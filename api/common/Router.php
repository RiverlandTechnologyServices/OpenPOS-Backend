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

    public function Route(string $uri): void
    {
        $requestedRoute = trim($uri, '/') ?? '/';
        foreach ($this->routes as $route => $action)
        {
            // Transform route to regex pattern.
            $routeRegex = preg_replace_callback('/{\w+(:([^}]+))?}/', function ($matches)
            {
                return isset($matches[1]) ? '(' . $matches[2] . ')' : '([a-zA-Z0-9_-]+)';
            }, $route);

            // Add the start and end delimiters.
            $routeRegex = '@^' . $routeRegex . '$@';

            // Check if the requested route matches the current route pattern.
            if (preg_match($routeRegex, $requestedRoute, $matches))
            {
                // Get all user requested path params values after removing the first matches.
                array_shift($matches);
                $routeParamsValues = $matches;

                // Find all route params names from route and save in $routeParamsNames
                $routeParamsNames = [];
                if (preg_match_all('/{(\w+)(:[^}]+)?}/', $route, $matches))
                {
                    $routeParamsNames = $matches[1];
                }

                // Combine between route parameter names and user provided parameter values.
                $routeParams = array_combine($routeParamsNames, $routeParamsValues);
                if($this->routes[$route])
                {
                    $this->routes[$route]->{strtolower($_SERVER['REQUEST_METHOD'])}($routeParams);
                    return;
                }
            }
        }

        // TODO: Send 404
    }

}