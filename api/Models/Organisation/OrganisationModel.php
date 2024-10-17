<?php
/**
 *  $DESCRIPTION$ $END$
 * @name OrganisationModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\Organisation;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class OrganisationModel extends BaseDatabaseModel implements BaseModelInterface
{
    protected string $id;
    protected string $name;
    protected string $taxID;
    protected bool $enabled;

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