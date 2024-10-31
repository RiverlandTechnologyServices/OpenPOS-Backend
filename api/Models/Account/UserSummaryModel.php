<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserSummaryModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

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
    public function __construct()
    {
        parent::__construct();

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

    /**
     * @param string $id
     * @param string $email
     * @param string $sessionToken
     * @return UserSummaryModel
     * @throws OpenPOSException
     */
    public static function Find(string $id = "", string $email = "", string $sessionToken = ""): UserSummaryModel
    {
        $requestedUser = new UserSummaryModel();
        if($id)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "userName", "email", "roleID", "globalRoleID", "enabled", "organisationID"])->from("users")->where()->variableName("id")->equals()->variable($id);
        }
        else if($email)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "userName", "email", "roleID", "globalRoleID", "enabled", "organisationID"])->from("users")->where()->variableName("email")->equals()->variable($email);
        }
        else if($sessionToken)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "userName", "email", "roleID", "globalRoleID", "enabled", "organisationID"])->from("users")->where()->variableName("sessionToken")->equals()->variable($sessionToken);
        }
        else
        {
            throw new OpenPOSException("Failed to provide ID, Email, or SessionToken", "UserModel", "no_token", "no_token");
        }

        //$data = ($this->execute("SELECT userName, email, sessionTokens, role, globalRole, enabled, userSettings, organisationID FROM users WHERE id = ?", [$id]))[0];
        $data = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt)->fetch_assoc()[0];
        $requestedUser->id = $data["id"];
        $requestedUser->userName = $data["userName"];
        $requestedUser->email = $data["email"];
        $requestedUser->roleID = $data["roleID"];
        $requestedUser->globalRoleID = $data["globalRoleID"];
        $requestedUser->enabled = $data["enabled"];
        $requestedUser->organisationID = $data["organisationID"];
        return $requestedUser;
    }

    /**
     * @return UserSummaryModel
     * @throws OpenPOSException
     */
    public static function Create(): UserSummaryModel
    {
        throw new OpenPOSException("Tried to create blank user summary, but there is nothing to create.", "UserSummaryModel","create_fail", "internal_error");
    }
}