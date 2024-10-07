<?php

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;

/**
 *  Helper utilities for interacting with databases
 * @name DBUtils.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

class SQLQuery
{
    protected string $stmt = "";
    protected array $parameters = [];

    public function select(array $variables): SQLQuery
    {
        $this->stmt = "SELECT ";
        for ($i = 0; $i < count($variables); $i++) {
            if ($i == 0) {
                $this->stmt .= $variables[$i];
            }
            else {
                $this->stmt .= ", " . $variables[$i];
            }
        }
        return $this;
    }

    public function insertInto(string $table, array $parameterNames, array $parameters): SQLQuery
    {
        if(count($parameterNames) != count($parameters))
        {
            throw new OpenPOSException("Parameter names must be the same length as parameters", "SQLQuery", "parameter_mismatch", "internal_error");
        }
        $this->stmt = "INSERT INTO " . $table . " " . implode(", ", $parameterNames) . " VALUES (" . str_repeat("?,", count($parameterNames)-1) . "?)";
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }

    public function from(string $table): SQLQuery
    {
        $this->stmt .= " FROM " . $table;
        return $this;
    }

    public function where(): SQLQuery
    {
        $this->stmt .= " WHERE";
        return $this;
    }

    public function variableName(string $variableName): SQLQuery
    {
        $this->stmt .= " " . $variableName;
        return $this;
    }

    public function equals(string $value = ""): SQLQuery
    {
        $this->stmt .= "=" . $value;
        return $this;
    }

    public function notEquals(string $value): SQLQuery
    {
        $this->stmt .= "!=" . $value;
        return $this;
    }

    public function greaterThan(string $value): SQLQuery
    {
        $this->stmt .= ">" . $value;
        return $this;
    }

    public function greaterThanEquals(string $value): SQLQuery
    {
        $this->stmt .= ">=" . $value;
        return $this;
    }

    public function lessThan(string $value): SQLQuery
    {
        $this->stmt .= "<" . $value;
        return $this;
    }

    public function lessThanEquals(string $value): SQLQuery
    {
        $this->stmt .= "<=" . $value;
        return $this;
    }

    public function like(string $value): SQLQuery
    {
        $this->stmt .= " LIKE " . $value;
        return $this;
    }

    public function and(): SQLQuery
    {
        $this->stmt .= " AND";
        return $this;
    }

    public function or(): SQLQuery
    {
        $this->stmt .= " OR";
        return $this;
    }

    public function variable($parameter): SQLQuery
    {
        $this->parameters[] = $parameter;
        $this->stmt .= "?";
        return $this;
    }

    public function string(string $value): SQLQuery
    {
        $this->stmt .= " " . $value;
        return $this;
    }

    public function getStmt(): string
    {
        return $this->stmt;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

}

class DatabaseManager
{
    private static DatabaseManager $instance;
    protected ?mysqli $connection = null;

    private function __construct()
    {
        try
        {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if($this->connection->connect_errno)
            {
                throw new OpenPOSException("Failed to connect to database", "BaseDatabaseModel", "db_connect_fail", "internal_error");
            }
        } catch (OpenPOSException $e) {
            Logger::error($e);
        }
    }

    public static function getInstance(): DatabaseManager
    {
        if (!isset(self::$instance)) {
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }

    /**
     * @throws OpenPOSException
     */
    public function execute(SQLQuery $stmt): \mysqli_result|bool|null
    {
        $results = $this->connection->execute_query($stmt->getStmt(), $stmt->getParameters());

        if($this->connection->errno)
            throw new OpenPOSException("Failed to submit database query", "DatabaseManager", "db_query_fail", "internal_error");

        return $results;
    }
}