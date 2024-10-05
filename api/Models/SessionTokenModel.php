<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokenModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class SessionTokenModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected string $id;
    protected bool $active;
    protected TimeModel $timeCreated;

    public function __construct($data)
    {
        parent::__construct();

        $this->id = $data["id"];
        $this->active = $data["active"];
        $this->timeCreated = $data["timeCreated"];
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "active" => $this->active,
            "timeCreated" => $this->timeCreated
        );
    }
}