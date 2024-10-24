<?php
/**
 *  $DESCRIPTION$ $END$
 * @name RoleModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use DatabaseManager;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;
use SQLQuery;

enum PermissionValues: string
{
    case NoAccess = "noAccess";
    case ReadOnly = "readOnly";
    case ReadWrite = "readWrite";
    public static function canRead(PermissionValues $permissionValues): bool
    {
        if($permissionValues == self::ReadOnly || $permissionValues == self::ReadWrite) {
            return true;
        }
        return false;
    }

    public static function canWrite(PermissionValues $permissionValues): bool
    {
        if($permissionValues == self::ReadWrite) {
            return true;
        }
        return false;
    }
}

class RoleModel extends BaseDatabaseModel implements BaseModelInterface
{
    protected string $id;

    protected string $name;
    protected string $organisationID;

    /***************
     * Permissions *
     ***************/
    /**
     * Permission for accessing Users within the organisation.
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

    /**
     * Permission for accessing Payment Types within the organisation.
     * @var PermissionValues
     */
    protected PermissionValues $paymentTypes;
    public function canReadPaymentTypes(): bool
    {
        return PermissionValues::canRead($this->paymentTypes);
    }
    public function canWritePaymentTypes(): bool
    {
        return PermissionValues::canWrite($this->paymentTypes);
    }

    function __construct()
    {
        parent::__construct();
    }

    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "organisationID" => $this->organisationID,
            "permissions" => array(
                "users" => $this->users,
            ),
        );
    }

    /**
     * @param string $roleID
     * @return RoleModel
     * @throws OpenPOSException
     */
    public static function Find(string $roleID = ""): RoleModel
    {
        if(!$roleID)
        {
            throw new OpenPOSException("No RoleModel ID provided", "PermissionsModel", "insufficient_input", "insufficient_input");
        }

        $permissions = new RoleModel();
        $stmt = (new \SQLQuery())->select(["*"])->from("roles")->where()->variableName("roleID")->equals()->variable($roleID);
        $result = DatabaseManager::getInstance()->execute($stmt)[0];
        $permissions->id = $result["id"];
        $permissions->name = $result["name"];
        $permissions->organisationID = $result["organisationID"];

        $permissions->users = $result["users"];
        return $permissions;
    }

    /**
     * @param string $name
     * @param string $organisationID
     * @return RoleModel
     * @throws OpenPOSException
     */
    public static function Create(string $name = "", string $organisationID = ""): RoleModel
    {
        if(!$name || !$organisationID)
        {
            throw new OpenPOSException("Role name or organisationID not provided", "RoleMode", "insufficient_inputs", "insufficient_inputs");
        }

        $stmt = (new SQLQuery())->select(["id"])->from("roles")->where()->variableName("roleName")->equals()->variable($name)->and()->variableName("organisationID")->equals()->variable($organisationID);

        $stmt = (new SQLQuery())->insertInto("roles", ["name", "organisationID", "users"], [$name, $organisationID, PermissionValues::NoAccess]);
        DatabaseManager::getInstance()->execute($stmt);
        $permissions = new RoleModel();
        $permissions->users = PermissionValues::NoAccess;
        return $permissions;
    }
}