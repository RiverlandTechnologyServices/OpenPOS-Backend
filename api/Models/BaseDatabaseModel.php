<?php
/**
 *  $DESCRIPTION$ $END$
 * @name BaseDatabaseModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use mysqli;
use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;

abstract class BaseDatabaseModel extends BaseModel implements BaseModelInterface
{


    public function __construct()
    {

    }

    abstract public static function Find(): BaseModelInterface;
    abstract public static function Create(): BaseModelInterface;


    abstract public function toArray(): array;
}