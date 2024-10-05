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

    public function __construct($id)
    {
        parent::__construct();

        $stmt = (new \SQLQuery())->select(["active", "timeCreated"])->from("sessionTokens")->where()->variableName("id")->equals()->variable($id);
        $result = $this->execute($stmt)[0];

        $this->id = $id;
        $this->active = $result["active"];
        $this->timeCreated = $result["timeCreated"];
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