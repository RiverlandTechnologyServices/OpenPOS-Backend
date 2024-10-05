<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UsersModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class UsersModel extends BaseDatabaseModel implements BaseModelInterface
{
    public function __construct(string $userName = "", string $organisationID = "", string $roleID = "", string $globalRoleID = "", bool $enabled = true, string $searchTerm = "")
    {
        parent::__construct();

        $stmt = (new \SQLQuery())->select(["id"])->from("users")->where()->variableName("userName")->like("%")->variable($userName)->variable($searchTerm)->string("%")->or()->variableName("organisationID")->like("%")->variable($organisationID)->variable($searchTerm)->string("%")->or()->variableName("roleID")->like("%")->variable($roleID)->variable($searchTerm)->string("%");

        $this->execute("SELECT id FROM users ORDER BY id DESC LIMIT ?", [$max]);
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}