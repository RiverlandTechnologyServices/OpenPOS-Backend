<?php
/**
 *  Basic Controller Functionality
 * @name BaseController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */
namespace OpenPOS\Controllers;

use OpenPOS\Common\Logger;
use OpenPOS\Models\Account\UserSummaryModel;
use OpenPOS\Models\PermissionsModel;

require_once __DIR__ . "/../common/bootstrap.inc.php";

interface BaseControllerInterface
{
    public function __call($name, $arguments);
    public function get(array $args);
    public function post(array $args);
    public function put(array $args);
    public function delete(array $args);
    public function patch(array $args);
    public function head(array $args);
    public function options(array $args);
}

/**
 * Basic Controller Class
 */
class BaseController implements BaseControllerInterface
{
    protected string $sessionToken;
    protected array $postBody;


    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function get(array $args): void
    {
        $sessionToken = $_GET['session_token'] ?? null;
        $this->sessionToken = $sessionToken;
    }

    public function post(array $args): void
    {
        $this->postBody = json_decode(file_get_contents('php://input'), true);
        $sessionToken = $this->postBody['session_token'] ?? null;
        $this->sessionToken = $sessionToken;
    }

    public function put(array $args): void
    {
        $this->postBody = json_decode(file_get_contents('php://input'), true);
        $sessionToken = $this->postBody['session_token'] ?? null;
        $this->sessionToken = $sessionToken;
    }

    public function delete(array $args): void
    {
        $this->postBody = json_decode(file_get_contents('php://input'), true);
        $sessionToken = $this->postBody['session_token'] ?? null;
        $this->sessionToken = $sessionToken;
    }

    public function patch(array $args): void
    {
        $this->postBody = json_decode(file_get_contents('php://input'), true);
        $sessionToken = $this->postBody['session_token'] ?? null;
        $this->sessionToken = $sessionToken;
    }

    public final function head(array $args): void
    {
        $this->get($args);
    }

    public function options(array $args): void
    {
        $sessionToken = $_GET['session_token'] ?? null;
        $this->sessionToken = $sessionToken;
    }

    protected function success(array $data): void
    {
        echo json_encode(array(
            "success" => true,
            "data" => $data
        ));
    }

    protected function error(string $errorCode): void
    {
        echo json_encode(array(
            "success" => false,
            "errorCode" => $errorCode
        ));
    }

    protected function getPermissions(string $sessionToken): ?PermissionsModel
    {
        try
        {
            $user = new UserSummaryModel("", "", $sessionToken);
            return new PermissionsModel($user->getRoleID());
        } catch (\Exception $e)
        {
            Logger::error($e);
        }
        return null;
    }
}