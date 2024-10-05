<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

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
     */
    public function __construct(string $id)
    {
        parent::__construct();
        $this->id = $id;
        //$data = ($this->execute("SELECT userName, email, sessionTokens, role, globalRole, enabled, userSettings, organisationID FROM users WHERE id = ?", [$id]))[0];
        $stmt = (new \SQLQuery())->select(["userName", "email", "password", "sessionTokens", "roleID", "globalRoleID", "enabled", "userSettings", "organisationID"])->from("users")->where()->variableName("id")->equals()->variable($id);
        $data = $this->execute($stmt);
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