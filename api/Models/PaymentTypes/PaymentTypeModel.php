<?php

namespace OpenPOS\Models\PaymentTypes;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

enum PaymentType: int
{
    case Cash = 0;
    case Card = 1;
    case GiftCard = 2;
    case Other = 100;
}

class PaymentTypeModel extends BaseDatabaseModel implements BaseModelInterface
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

    protected string $readableName;

    public function getReadableName(): string
    {
        return $this->readableName;
    }

    protected string $organisationId;

    public function getOrganisationID(): string
    {
        return $this->organisationId;
    }
    protected string $locationId;
    public function getLocationID(): string
    {
        return $this->locationId;
    }
    protected PaymentType $paymentType;
    public function getPaymentType(): PaymentType
    {
        return $this->paymentType;
    }

    protected array $paymentTypeSettings;
    public function getPaymentTypeSettings(): array
    {
        return $this->paymentTypeSettings;
    }

    function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws OpenPOSException
     */
    public static function Find(string $id = ""): self
    {
        $paymentType = new self();
        if($id)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "name", "readableName", "organisationId", "locationId", "paymentType", "paymentTypeSettings"])->from("paymentTypes")->where()->variableName("id")->equals()->variable($id);
        }
        else
        {
            throw new OpenPOSException("Failed to provide ID", "PaymentTypeModel", "insufficient_inputs", "insufficient_inputs");
        }

        if($data = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt)->fetch_all()[0])
        {
            $paymentType->id = $data["id"];
            $paymentType->name = $data["name"];
            $paymentType->readableName = $data["readableName"];
            $paymentType->organisationId = $data["organisationId"];
            $paymentType->locationId = $data["locationId"];
            $paymentType->paymentType = $data["paymentType"];
            $paymentType->paymentTypeSettings = $data["paymentTypeSettings"];
            return $paymentType;
        }
        else
        {
            throw new OpenPOSException("No Payment Type with ID found", "PaymentTypeModel", "no_payment_type", "no_payment_type");
        }
    }

    public static function Create(string $name = "", string $readableName = "", string $organisationId = "", string $locationId = ""): self
    {
        throw new OpenPOSException("Cannot create a PaymentTypeModel", "PaymentTypeModel", "create_payment_type_error", "internal_error");
    }

    /**
     * @throws OpenPOSException
     */
    public static function CreatePaymentType(string $name = "", string $readableName = "", string $organisationId = "", string $locationId = "", PaymentType $paymentType = PaymentType::Other, array $paymentTypeSettings = array()): self
    {
        if(!$name || !$readableName || !$organisationId || !$locationId || !$paymentType || !$paymentTypeSettings)
        {
            throw new OpenPOSException("Failed to provide all inputs", "PaymentTypeModel", "insufficient_inputs", "insufficient_inputs");
        }

        $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id"])->from("paymentTypes")->where()->variableName("name")->equals()->variable($name)->and()->variableName("organisationId")->equals()->variable($organisationId);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if(!$result)
        {
            throw new OpenPOSException("Failed to check PaymentTypes!", "PaymentTypeModel", "lookup_failed", "internal_error");
        }

        if($result->lengths != 0)
        {
            throw new OpenPOSException("PaymentType with name already exists!", "PaymentTypeModel", "payment_type_exists", "payment_type_exists");
        }


        $stmt = (new \OpenPOS\Common\SQLQuery())->insertInto("paymentTypes", ["name", "readableName", "organisationId", "locationId", "paymentType", "paymentTypeSettings"], [$name, $readableName, $organisationId, $locationId, $paymentType, json_encode($paymentTypeSettings)]);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if(!$result)
        {
            throw new OpenPOSException("Failed to create new PaymentType!", "PaymentTypeModel", "create_fail", "internal_error");
        }

        $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id"])->from("paymentTypes")->where()->variableName("name")->equals()->variable($name)->and()->variableName("organisationId")->equals()->variable($organisationId);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if(!$result)
        {
            throw new OpenPOSException("Failed to create new PaymentType!", "PaymentTypeModel", "create_fail", "internal_error");
        }

        return PaymentTypeModel::Find($result[0]->id);
    }


    public function toArray(): array
    {
        return array(

        );
    }
}