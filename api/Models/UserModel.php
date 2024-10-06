<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;

class UserModel extends BaseDatabaseModel implements BaseModelInterface
{
    /**
     * @var string
     */
    protected string $id;
    /**
     * @var string
     */
    protected string $userName;
    /**
     * @var string
     */
    protected string $email;
    /**
     * @var string
     */
    protected string $password;
    /**
     * @var SessionTokensModel
     */
    protected SessionTokensModel $sessionTokens;
    /**
     * @var string
     */
    protected string $roleID;
    /**
     * @var string
     */
    protected string $globalRoleID;
    /**
     * @var bool
     */
    protected bool $enabled;
    /**
     * @var UserSettingsModel
     */
    protected UserSettingsModel $userSettings;
    /**
     * @var string
     */
    protected string $organisationID;

    /**
     * @param string $id
     * @param string $email
     * @param string $sessionToken
     * @throws OpenPOSException
     */
    public function __construct(string $id = "", string $email = "", string $sessionToken = "")
    {
        parent::__construct();
        if($id)
        {
            $stmt = (new \SQLQuery())->select(["id", "userName", "email", "password", "sessionTokens", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("id")->equals()->variable($id);

        }
        else if($email)
        {
            $stmt = (new \SQLQuery())->select(["id", "userName", "email", "password", "sessionTokens", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("email")->equals()->variable($email);
        }
        else if($sessionToken)
        {
            $stmt = (new \SQLQuery())->select(["id", "userName", "email", "password", "sessionTokens", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("sessionToken")->equals()->variable($sessionToken);
        }
        else
        {
            throw new OpenPOSException("Failed to provide ID, Email, or SessionToken", "UserModel", "no_token", "no_token");
        }

        //$data = ($this->execute("SELECT userName, email, sessionTokens, role, globalRole, enabled, userSettings, organisationID FROM users WHERE id = ?", [$id]))[0];
        $data = $this->execute($stmt)[0];
        $this->id = $data["id"];
        $this->userName = $data["userName"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->sessionTokens = new SessionTokensModel(json_decode($data["sessionTokens"]));
        $this->roleID = $data["roleID"];
        $this->globalRoleID = $data["globalRoleID"];
        $this->enabled = $data["enabled"];
        $this->userSettings = new UserSettingsModel(json_decode($data["userSettings"]));
        $this->organisationID = $data["organisationID"];
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
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
        "sessionTokens" => $this->sessionTokens->toArray(),
        "roleID" => $this->roleID,
        "globalRoleID" => $this->globalRoleID,
        "enabled" => $this->enabled,
        "userSettings" => $this->userSettings->toArray(),
        "organisationID" => $this->organisationID,
        );
    }
}