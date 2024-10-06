<?php
/**
 *  $DESCRIPTION$ $END$
 * @name PermissionsModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

enum PermissionValues: string
{
    case NoAccess = "noAccess";
    case ReadOnly = "readOnly";
    case ReadWrite = "readWrite";
    public static function canRead(PermissionValues $permissionValues)
    {
        if($permissionValues == self::ReadOnly || $permissionValues == self::ReadWrite) {
            return true;
        }
        return false;
    }

    public static function canWrite(PermissionValues $permissionValues)
    {
        if($permissionValues == self::ReadWrite) {
            return true;
        }
        return false;
    }
}

class PermissionsModel extends BaseDatabaseModel implements BaseModelInterface
{

    /**
     * @var string
     */
    protected string $roleID;
    /**
     * Permission for accessing users within the organisation.
     * @var PermissionValues
     */
    protected PermissionValues $users;
    public function canReadUsers(): bool
    {
        return PermissionValues::canRead($this->users);
    }
    public function canWriteUsers(): bool
    {
        return PermissionValues::canWrite($this->users);
    }

    function __construct($roleID)
    {
        parent::__construct();

        $stmt = (new \SQLQuery())->select(["*"])->from("roles")->where()->variableName("roleID")->equals()->variable($roleID);
        $result = $this->execute($stmt)[0];
        $this->users = $result["users"];
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}