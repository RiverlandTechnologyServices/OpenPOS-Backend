<?php
/**
 *  $DESCRIPTION$ $END$
 * @name PermissionsModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use DatabaseManager;
use OpenPOS\Common\OpenPOSException;
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

    function __construct()
    {
        parent::__construct();
    }

    public function toArray(): array
    {
        return array(
            "users" => $this->users,
        );
    }

    /**
     * @param string $roleID
     * @return PermissionsModel
     * @throws OpenPOSException
     */
    public static function Find(string $roleID = ""): PermissionsModel
    {
        if(!$roleID)
        {
            throw new OpenPOSException("No Role ID provided", "PermissionsModel", "insufficient_input", "insufficient_input");
        }

        $permissions = new PermissionsModel();
        $stmt = (new \SQLQuery())->select(["*"])->from("roles")->where()->variableName("roleID")->equals()->variable($roleID);
        $result = DatabaseManager::getInstance()->execute($stmt)[0];
        $permissions->users = $result["users"];
        return $permissions;
    }

    /**
     * @return BaseModelInterface
     */
    public static function Create(): BaseModelInterface
    {
        $permissions = new PermissionsModel();
        $permissions->users = PermissionValues::NoAccess;
        return $permissions;
    }
}