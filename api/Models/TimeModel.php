<?php
/**
 * Class for representing Time as a unix timestamp, and displaying strings based on Timezone provided
 * @name TimeModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseModelInterface;

class TimeModel extends BaseModel implements BaseModelInterface
{

    protected int $unixTimestamp;

    public function __construct($unixTimestamp)
    {
        $this->unixTimestamp = $unixTimestamp;
    }

    public function getUnixTimestamp(): int
    {
        return $this->unixTimestamp;
    }

    /**
     * @throws OpenPOSException
     */
    public function toString(string $timezone = null): string
    {
        $date = new \DateTime();
        try
        {
            $date->setTimezone(new \DateTimeZone($timezone));
        } catch (\DateInvalidTimeZoneException $e) {
            throw new OpenPOSException($e->getMessage(), "TimeModel", "invalid_timezone", "invalid_timezone");
        }
        $date->setTimestamp($this->unixTimestamp);
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