<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

class UserModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected string $id;
    protected string $userName;
    protected string $email;
    protected string $password;
    protected SessionTokensModel $sessionTokens;
    protected string $role;
    protected string $globalRole;
    protected bool $enabled;
    protected UserSettingsModel $userSettings;
    protected string $organisationID;


    public function __construct(string $id)
    {
        parent::__construct();
        $this->id = $id;
        //$data = ($this->execute("SELECT userName, email, sessionTokens, role, globalRole, enabled, userSettings, organisationID FROM users WHERE id = ?", [$id]))[0];
        $stmt = (new \SQLQuery())->select(["userName", "email", "sessionTokens", "role", "globalRole", "enabled", "userSettings", "organisationID"])->where()->variableName("id")->equals()->variable($id);
        $data = $this->execute($stmt);
        $this->userName = $data["userName"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->sessionTokens = new SessionTokensModel(json_decode($data["sessionTokens"]));
        $this->role = $data["role"];
        $this->globalRole = $data["globalRole"];
        $this->enabled = $data["enabled"];
        $this->userSettings = new UserSettingsModel(json_decode($data["userSettings"]));
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
            "sessionTokens" => $this->sessionTokens->toArray(),
            "role" => $this->role,
            "globalRole" => $this->globalRole,
            "enabled" => $this->enabled,
            "userSettings" => $this->userSettings->toArray(),
            "organisationID" => $this->organisationID,
        );
    }
}