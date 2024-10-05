<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserSettingsModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseModelInterface;

class UserSettingsModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected string $timezone;


    public function __construct(array $data)
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "timezone" => $this->timezone,
        );
    }
}