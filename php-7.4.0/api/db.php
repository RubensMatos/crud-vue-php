<?php

class Db {

    private $host;
    private $port;
    private $database;
    private $user;
    private $password;
    private $connection;

    public function __construct() {

        $this->host     = "localhost";
        $this->port     = 5432;
        $this->database = "workana";
        $this->user     = "postgres";
        $this->password = "123456";

        $this->connect();
    }

    public function connect() {

        $connectionString = "host=$this->host port=$this->port dbname=$this->database user=$this->user password=$this->password";
        $this->conn  = pg_connect($connectionString);
    }

    public function query($query, $params = []) {
        $result = pg_query_params($this->conn, $query, $params);
        return $result;
    }

    public function close() {
        if ($this->conn) {
            pg_close($this->conn);
            $this->conn = null;
        }
    }

    public function insertRecord($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        print_r($columns);
        print_r($placeholders);

        exit();

       
        $values = array_values($data);
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        return $this->query($query, $values);
    }

    public function updateRecord($table, $data, $condition, $conditionParams = []) {
        $setClause = '';
        $values = array_values($data);
        foreach (array_keys($data) as $column) {
            $setClause .= "$column = ?, ";
        }
        $setClause = rtrim($setClause, ', ');

        $query = "UPDATE $table SET $setClause WHERE $condition";
        $params = array_merge($values, $conditionParams);
        return $this->query($query, $params);
    }

    public function deleteRecord($table, $condition, $conditionParams = []) {
        $query = "DELETE FROM $table WHERE $condition";
        return $this->query($query, $conditionParams);
    }
}

?>
