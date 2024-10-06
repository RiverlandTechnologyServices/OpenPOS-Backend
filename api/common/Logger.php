<?php
/**
 *  Implements functions for logging errors and other information
 * @name Logger.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Common;

// TODO Implement Logger with Prometheus/Alternate Backends
class Logger
{

    public static function info(string $message)
    {

    }

    public static function warn(string $message)
    {

    }

    /**
     * Logs an error to logging backend
     * @param string|OpenPOSException $message
     * @return void
     */
    public static function error (string|OpenPOSException $message)
    {

    }

}