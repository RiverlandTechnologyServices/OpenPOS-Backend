<?php
/**
 *  $DESCRIPTION$ $END$
 * @name RoleModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Account;

use Couchbase\Role;
use OpenPOS\Common\DatabaseManager;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;
use OpenPOS\Common\SQLQuery;

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
    public function getID(): string
    {
        return $this->id;
    }

    protected string $name;
    public function getName(): string
    {
        return $this->name;
    }
    protected string $organisationID;
    public function getOrganisationID(): string
    {
        return $this->organisationID;
    }

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
    public static function Find(string $roleID = "", string $name = "", string $organisationID = ""): RoleModel
    {
        if(!$roleID || (!$name && !$organisationID))
        {
            throw new OpenPOSException("No Role ID provided", "PermissionsModel", "insufficient_input", "insufficient_input");
        }

        if($roleID)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["*"])->from("roles")->where()->variableName("roleID")->equals()->variable($roleID);
        }
        if($name && $organisationID)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["*"])->from("roles")->where()->variableName("name")->equals()->variable($name)->and()->variableName("organisationID")->equals()->variable($organisationID)->equals()->variable($organisationID);
        }

        $permissions = new RoleModel();
        $result = DatabaseManager::getInstance()->execute($stmt)->fetch_all(MYSQLI_ASSOC)[0];
        $permissions->id = $result["id"];
        $permissions->name = $result["name"];
        $permissions->organisationID = $result["organisationID"];
        $permissions->users = $result["users"];
        $permissions->paymentTypes = $result["paymentTypes"];
        return $permissions;
    }

    /**
     * @param string $name
     * @param string $organisationID
     * @return RoleModel
     * @throws OpenPOSException
     */
    public static function Create(string $name = "", string $organisationID = "", PermissionValues $users = PermissionValues::NoAccess, PermissionValues $paymentTypes = PermissionValues::NoAccess): RoleModel
    {
        if(!$name || !$organisationID)
        {
            throw new \OpenPOS\Common\OpenPOSException("Role name or organisationID not provided", "RoleMode", "insufficient_inputs", "insufficient_inputs");
        }

        $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id"])->from("roles")->where()->variableName("roleName")->equals()->variable($name)->and()->variableName("organisationID")->equals()->variable($organisationID);

        $stmt = (new \OpenPOS\Common\SQLQuery())->insertInto("roles", ["name", "organisationID", "users", "paymentTypes"], [$name, $organisationID, $users->value, $paymentTypes->value]);
        \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        return RoleModel::Find("", $name, $organisationID);
    }
}