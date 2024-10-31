<?php
/**
 *  $DESCRIPTION$ $END$
 * @name \OpenPOS\Models\Account\UsersModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class UsersModel extends BaseDatabaseModel implements BaseModelInterface
{
    protected array $users = [];
    protected int $count = 0;

    /**
     */
    protected function __construct()
    {
        parent::__construct();
    }

    public function toArray(): array
    {
        return(array(
            "users" => $this->users,
        ));
    }

    /**
     * @param string $userName
     * @param string $organisationID
     * @param string $roleID
     * @param string $globalRoleID
     * @param bool $enabled
     * @param string $searchTerm
     * @return UsersModel
     * @throws OpenPOSException
     */
    public static function Find(string $userName = "", string $organisationID = "", string $roleID = "", string $globalRoleID = "", bool $enabled = true, string $searchTerm = ""): UsersModel
    {
        $users = new UsersModel();
        $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id"])->from("users")->where()->variableName("userName")->like("%")->variable($userName)->variable($searchTerm)->string("%")->or()->variableName("organisationID")->like("%")->variable($organisationID)->variable($searchTerm)->string("%")->or()->variableName("roleID")->like("%")->variable($roleID)->variable($searchTerm)->string("%")->or()->variableName("globalRoleID")->like("%")->variable($globalRoleID)->variable($searchTerm)->string("%")->or()->variableName("enabled")->equals()->variable($enabled);

        $results = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt)->fetch_assoc();
        foreach ($results as $result) {
            try {
                $users->users[] = UserSummaryModel::Find($result["id"]);
            } catch (OpenPOSException $e) {
                throw new OpenPOSException("Empty ID returned from SQL query.", "UsersModel","empty_result", "internal_error");
            }
            $users->count++;
        }
        return $users;
    }

    /**
     * @return BaseModelInterface
     * @throws OpenPOSException
     */
    public static function Create(): BaseModelInterface
    {
        throw new OpenPOSException("Tried to create users list, but there is nothing to create.", "UsersModel","create_fail", "internal_error");
    }
}