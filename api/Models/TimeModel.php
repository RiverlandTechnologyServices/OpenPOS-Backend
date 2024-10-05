<?php
/**
 * Class for representing Time as a unix timestamp, and displaying strings based on Timezone provided
 * @name TimeModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseModelInterface;

class TimeModel implements BaseModelInterface
{

    protected int $unixTimestamp;

    public function __construct($unixTimestamp)
    {
        $this->unixTimestamp = $unixTimestamp;
    }

    public function getUnixTimestamp()
    {
        return $this->unixTimestamp;
    }

    public function toString(string $timezone = null)
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone($timezone));
        return $date->format('d-m-Y H:i:s');
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "unixTimestamp" => $this->unixTimestamp
        );
    }
}