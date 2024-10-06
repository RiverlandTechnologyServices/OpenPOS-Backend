<?php
/**
 *  $DESCRIPTION$ $END$
 * @name OpenPOSException.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Common;

class OpenPOSException extends \Exception
{

    protected string $errorClassName;
    protected string $internalCode;
    protected string $publicCode;
    protected $message;

    public function __construct(string $message, string $errorClassName, string $internalCode, string $publicCode)
    {
        parent::__construct($message);
        $this->errorClassName = $errorClassName;
        $this->internalCode = $internalCode;
        $this->publicCode = $publicCode;
    }

    public function getInternalCode(): string
    {
        return $this->internalCode;
    }

    public function getPublicCode(): string
    {
        return $this->publicCode;
    }

}