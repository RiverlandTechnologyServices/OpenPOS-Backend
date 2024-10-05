<?php
/**
 *  Basic Controller Class
 * @name BaseController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

require_once __DIR__ . "/../common/bootstrap.inc.php";

class BaseController
{
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    protected function getURL()
    {
        return $_SERVER["REQUEST_URI"];
    }

    protected function getPath()
    {
        return parse_url($this->getURL(), PHP_URL_PATH);
    }

    protected function getURLSegments()
    {
        return explode("/", $this->getPath());
    }

    protected function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    protected function getRequestInput()
    {
        switch ($this->getMethod()) {
            case "GET":
                return $_GET;
                break;
            case "UPDATE":
            case "POST":
                json_decode(file_get_contents('php://input'));
                break;
        }
    }
}