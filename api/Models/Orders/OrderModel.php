<?php
/**
 *  $DESCRIPTION$ $END$
 * @name OrderModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Orders;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;
use OpenPOS\Models\TimeModel;

class OrderModel extends BaseDatabaseModel implements BaseModelInterface
{
    protected string $id;
    protected array $items;
    protected string $paymentID;
    protected string|null $customer;
    protected string|null $discountCodeID;
    protected string|null $publicNotes;
    protected string|null $privateNotes;
    protected string|null $tableID;
    protected TimeModel $orderTime;



    public static function Find(): BaseModelInterface
    {
        // TODO: Implement Find() method.
    }

    public static function Create(): BaseModelInterface
    {
        // TODO: Implement Create() method.
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}