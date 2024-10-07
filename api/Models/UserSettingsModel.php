<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserSettingsModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseModelInterface;

class UserSettingsModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected string $timezone;


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
            "timezone" => $this->timezone,
        );
    }

    /**
     * @param string $userID
     * @return UserSettingsModel
     * @throws OpenPOSException
     */
    public static function Find(string $userID = ""): UserSettingsModel
    {
        if(!$userID)
        {
            throw new OpenPOSException("No Role ID provided", "PermissionsModel", "insufficient_input", "insufficient_input");
        }
        $userSettings = new UserSettingsModel();
        $stmt = (new \SQLQuery())->select(["userSettings"])->from("users")->where()->variableName("id")->equals()->variable($userID);

        $result = json_decode(\DatabaseManager::getInstance()->execute($stmt)[0]);
        foreach ($result as $setting => $value) {
            $userSettings->{$setting} = $value;
        }
        return $userSettings;
    }

    /**
     * @return UserSettingsModel
     */
    public static function Create(): UserSettingsModel
    {
        // TODO: Implement Create() method.
    }
}