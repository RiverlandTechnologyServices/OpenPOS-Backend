<?php
/**
 *  Basic Controller Functionality
 * @name BaseController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */
namespace OpenPOS\Controllers;

require_once __DIR__ . "/../common/bootstrap.inc.php";

interface BaseControllerInterface
{
    public function __call($name, $arguments);
    public function get(string $uri);
    public function post(string $uri);
    public function put(string $uri);
    public function delete(string $uri);
    public function patch(string $uri);
    public function head(string $uri);
    public function options(string $uri);
}

/**
 * Basic Controller Class
 */
class BaseController implements BaseControllerInterface
{
    protected string $uri;


    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function get(string $uri)
    {
        $this->uri = $uri;
    }

    public function post(string $uri)
    {
        $this->uri = $uri;
    }

    public function put(string $uri)
    {
        $this->uri = $uri;
    }

    public function delete(string $uri)
    {
        $this->uri = $uri;
    }

    public function patch(string $uri)
    {
        $this->uri = $uri;
    }

    public function head(string $uri)
    {
        $this->uri = $uri;
    }

    public function options(string $uri)
    {
        $this->uri = $uri;
    }


}