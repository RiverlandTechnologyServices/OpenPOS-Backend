<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserSummaryModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;

class UserSummaryModel extends BaseDatabaseModel implements BaseModelInterface
{

    /**
     * @var string
     */
    protected string $id;
    public function getID(): string
    {
        return $this->id;
    }
    /**
     * @var string
     */
    protected string $userName;
    public function getUserName(): string
    {
        return $this->userName;
    }
    /**
     * @var string
     */
    protected string $email;
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * @var string
     */
    protected string $roleID;
    public function getRoleID(): string
    {
        return $this->roleID;
    }
    /**
     * @var string
     */
    protected string $globalRoleID;
    public function getGlobalRoleID(): string
    {
        return $this->globalRoleID;
    }
    /**
     * @var bool
     */
    protected bool $enabled;
    public function getEnabled(): bool
    {
        return $this->enabled;
    }
    /**
     * @var string
     */
    protected string $organisationID;
    public function getOrganisationID(): string
    {
        return $this->organisationID;
    }

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
        $this->roleID = $data["roleID"];
        $this->globalRoleID = $data["globalRoleID"];
        $this->enabled = $data["enabled"];
        $this->organisationID = $data["organisationID"];
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
            "organisationID" => $this->organisationID,
        );
    }
}