<?php
/**
 *  Basic Model Class 
 * @name BaseModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

/**
 * Basic Model Class
 */
interface BaseModelInterface
{
    public function toJson(): string;
    public function toArray(): array;
}

abstract class BaseModel implements BaseModelInterface
{

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}