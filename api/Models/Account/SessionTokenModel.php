<?php
/**
 *  $DESCRIPTION$ $END$
 * @name \OpenPOS\Models\Account\SessionTokenModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;
use OpenPOS\Models\TimeModel;

class SessionTokenModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected string $id;
    public function getID(): string
    {
        return $this->id;
    }
    protected string $token;
    public function getToken(): string
    {
        return $this->token;
    }
    protected string $userID;
    public function getUserID(): string
    {
        return $this->userID;
    }
    protected bool $active;
    public function getActive(): bool
    {
        return $this->active;
    }
    protected TimeModel $timeCreated;
    public function getTimeCreated(): TimeModel
    {
        return $this->timeCreated;
    }

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
            "token" => $this->token,
            "userID" => $this->userID,
            "active" => $this->active,
            "timeCreated" => $this->timeCreated
        );
    }

    /**
     * @param string $id
     * @return SessionTokenModel
     * @throws OpenPOSException
     */
    public static function Find(string $id = "", string $token = ""): SessionTokenModel
    {
        if($id)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "token", "active", "timeCreated"])->from("sessionTokens")->where()->variableName("id")->equals()->variable($id);
        }
        else if($token)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "token", "active", "timeCreated"])->from("sessionTokens")->where()->variableName("token")->equals()->variable($token);
        }
        else
        {
            throw new OpenPOSException("No Session ID or Token provided", "SessionTokenModel", "insufficient_input", "insufficient_input");
        }
        $sessionToken = new SessionTokenModel();
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt)->fetch_assoc()[0];

        $sessionToken->id = $result["id"];
        $sessionToken->token = $result["token"];
        $sessionToken->active = $result["active"];
        $sessionToken->timeCreated = $result["timeCreated"];
        return $sessionToken;
    }

    /**
     * @param string $email
     * @param string $password
     * @return SessionTokenModel
     * @throws OpenPOSException
     */
    public static function Create(string $email = "", string $password = ""): SessionTokenModel
    {
        if(!$email || !$password)
        {
            throw new OpenPOSException("Failed to provide UserName, Email, Password, OrganisationID, RoleID, or GlobalRoleID", "UserModel", "insufficient_inputs", "insufficient_inputs");
        }

        try {
            $user = UserModel::Find("", $email);
            if(password_verify($password, $user->getPassword()))
            {
                $randomiser = new \Random\Randomizer(new \Random\Engine\Secure());
                $sessionToken = $randomiser->getBytesFromString("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#%^&*()_+-=", 128);
                $stmt = (new \OpenPOS\Common\SQLQuery())->insertInto("sessionTokens", ["userID", "token", "active", "timeCreated"], [$user->getID(), $sessionToken, true, time()]);
                $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
                if(!$result)
                {
                    throw new OpenPOSException("Failed to create new session!", "SessionTokenModel", "create_fail", "internal_error");
                }
                return SessionTokenModel::Find("", $sessionToken);
            }
            else
            {
                throw new OpenPOSException("Invalid Login", "SessionTokenModel", "invalid_login", "invalid_login");
            }
        }
        catch (OpenPOSException $e)
        {
            if ($e->getInternalCode() == "no_user")
            {
                throw new OpenPOSException("Invalid Login", "SessionTokenModel", "invalid_login", "invalid_login");
            }
            else
            {
                throw $e;
            }
        }
    }
}