<?php
/**
 *  $DESCRIPTION$ $END$
 * @name \OpenPOS\Models\Account\SessionTokensModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class SessionTokensModel extends BaseDatabaseModel implements BaseModelInterface
{

    /**
     * @var array
     */
    protected array $sessionTokens;

    /**
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
        return $this->sessionTokens;
    }

    /**
     * @param string $userID
     * @return SessionTokensModel
     * @throws OpenPOSException
     */
    public static function Find(string $userID = ""): SessionTokensModel
    {
        if(!$userID)
        {
            throw new OpenPOSException("No RoleModel ID provided", "PermissionsModel", "insufficient_input", "insufficient_input");
        }
        $sessionTokens = new SessionTokensModel();
        $stmt = (new \SQLQuery())->select(["id"])->from("sessionTokens")->where()->variableName("userID")->equals()->variable($userID);

        $results = \DatabaseManager::getInstance()->execute($stmt);
        $sessionTokens->sessionTokens = array();
        foreach ($results as $result) {
            $sessionTokens->sessionTokens[] = $result["id"];
        }
        return $sessionTokens;
    }

    /**
     * @return SessionTokensModel
     */
    public static function Create(): SessionTokensModel
    {
        $sessionTokens = new SessionTokensModel();
        $sessionTokens->sessionTokens = array();
        return $sessionTokens;
    }
}