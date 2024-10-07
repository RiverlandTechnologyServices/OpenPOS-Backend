<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokenModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class SessionTokenModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected string $id;
    protected bool $active;
    protected TimeModel $timeCreated;

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
            "active" => $this->active,
            "timeCreated" => $this->timeCreated
        );
    }

    /**
     * @param string $id
     * @return SessionTokenModel
     * @throws OpenPOSException
     */
    public static function Find(string $id = ""): SessionTokenModel
    {
        if(!$id)
        {
            throw new OpenPOSException("No Role ID provided", "PermissionsModel", "insufficient_input", "insufficient_input");
        }
        $sessionToken = new SessionTokenModel();
        $stmt = (new \SQLQuery())->select(["active", "timeCreated"])->from("sessionTokens")->where()->variableName("id")->equals()->variable($id);
        $result = \DatabaseManager::getInstance()->execute($stmt)[0];

        $sessionToken->id = $id;
        $sessionToken->active = $result["active"];
        $sessionToken->timeCreated = $result["timeCreated"];
        return $sessionToken;
    }

    /**
     * @return SessionTokenModel
     */
    public static function Create(): SessionTokenModel
    {
        // TODO: Implement Create() method.
    }
}