<?php
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

    public function variable($parameter): SQLQuery
    {
        $this->parameters[] = $parameter;
        $this->stmt .= "?";
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