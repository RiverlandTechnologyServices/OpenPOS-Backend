<?php
/**
 *  $DESCRIPTION$ $END$
 * @name BaseDatabaseModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use mysqli;
use OpenPOS\Common\Logger;

abstract class BaseDatabaseModel extends BaseModel implements BaseModelInterface
{

    protected ?mysqli $connection = null;

    public function __construct()
    {
        try
        {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if($this->connection->connect_errno)
            {
                throw new \Exception("Failed to connect to MySQL: " . $this->connection->connect_error);
            }
        } catch (\Exception $e) {
            Logger::error($e);
        }
    }

    protected function execute(\SQLQuery $stmt): \mysqli_result|bool|null
    {
        try {
            $results = $this->connection->execute_query($stmt->getStmt(), $stmt->getParameters());

            if($this->connection->errno)
                throw new \Exception("Failed to execute query: " . $this->connection->error);

            return $results;

        } catch (\Exception $e)
        {
            Logger::error($e);
        }
        return null;
    }

    abstract public function toArray(): array;
}