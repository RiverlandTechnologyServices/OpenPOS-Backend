<?php
/**
 *  $DESCRIPTION$ $END$
 * @name \OpenPOS\Models\Account\UserModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use \OpenPOS\Common\DatabaseManager;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

/**
 *
 */
class UserModel extends BaseDatabaseModel implements BaseModelInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->id;
    }
    /**
     * @var string
     */
    protected string $userName;

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
    /**
     * @var string
     */
    protected string $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * @var string
     */
    protected string $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * @var string
     */
    protected string $roleID;

    /**
     * @return string
     */
    public function getRoleID(): string
    {
        return $this->roleID;
    }
    /**
     * @var string
     */
    protected string $globalRoleID;

    /**
     * @return string
     */
    public function getGlobalRoleID(): string
    {
        return $this->globalRoleID;
    }
    /**
     * @var bool
     */
    protected bool $enabled;

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }
    /**
     * @var UserSettingsModel
     */
    protected UserSettingsModel $userSettings;

    /**
     * @return UserSettingsModel
     */
    public function getUserSettings(): UserSettingsModel
    {
        return $this->userSettings;
    }
    /**
     * @var string
     */
    protected string $organisationID;

    /**
     * @return string
     */
    public function getOrganisationID(): string
    {
        return $this->organisationID;
    }

    /**
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param string $id
     * @param string $email
     * @param string $sessionToken
     * @return UserModel
     * @throws OpenPOSException
     */
    public static function Find(string $id = "", string $email = "", string $sessionToken = ""): UserModel
    {
        $requestedUser = new UserModel();
        if($id)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "userName", "email", "password", "sessionTokens", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("id")->equals()->variable($id);
        }
        else if($email)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "userName", "email", "password", "sessionTokens", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("email")->equals()->variable($email);
        }
        else if($sessionToken)
        {
            $sessionTokenObject = SessionTokenModel::Find("", $sessionToken);
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "userName", "email", "password", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("id")->equals()->variable($sessionTokenObject->getUserID());
        }
        else
        {
            throw new OpenPOSException("Failed to provide ID, Email, or SessionToken", "UserModel", "no_token", "no_token");
        }

        //$data = ($this->execute("SELECT userName, email, sessionTokens, role, globalRole, enabled, userSettings, organisationID FROM users WHERE id = ?", [$id]))[0];

        if($data = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt)[0])
        {
            $requestedUser->id = $data["id"];
            $requestedUser->userName = $data["userName"];
            $requestedUser->email = $data["email"];
            $requestedUser->password = $data["password"];
            $requestedUser->roleID = $data["roleID"];
            $requestedUser->globalRoleID = $data["globalRoleID"];
            $requestedUser->enabled = $data["enabled"];
            $requestedUser->userSettings = UserSettingsModel::Find($data["id"]);
            $requestedUser->organisationID = $data["organisationID"];
            return $requestedUser;
        }
        else
        {
            throw new OpenPOSException("No user with ID, Email, or SessionToken found", "UserModel", "no_user", "no_user");
        }
    }

    /**
     * @throws OpenPOSException
     */
    public static function Create($userName = "", $email = "", $password = "", $organisationID = "", $roleID = "", $globalRoleID = "user", $enabled = true): UserModel
    {
        if(!$userName || !$email || !$password || !$organisationID || !$roleID || !$globalRoleID)
        {
            throw new OpenPOSException("Failed to provide UserName, Email, Password, OrganisationID, RoleID, or GlobalRoleID", "UserModel", "insufficient_inputs", "insufficient_inputs");
        }

        $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id"])->from("users")->where()->variableName("email")->equals()->variable($email);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if($result->num_rows != 0)
        {
            throw new OpenPOSException("User already exists", "UserModel", "user_already_exists", "user_already_exists");
        }

        $stmt = (new \OpenPOS\Common\SQLQuery())->insertInto("users", ["userName", "email", "password", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"], [$userName, $email, password_hash($password, PASSWORD_DEFAULT), $roleID, $globalRoleID, $enabled, UserSettingsModel::Create()->toJson(), $organisationID]);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if(!$result)
        {
            throw new OpenPOSException("Failed to create new user!", "UserModel", "create_fail", "internal_error");
        }

        return UserModel::Find("", $email);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "userName" => $this->userName,
            "email" => $this->email,
            "roleID" => $this->roleID,
            "globalRoleID" => $this->globalRoleID,
            "enabled" => $this->enabled,
            "userSettings" => $this->userSettings->toArray(),
            "organisationID" => $this->organisationID,
        );
    }
}