<?php

class Db {

    private $host;
    private $port;
    private $database;
    private $user;
    private $password;
    private $connection;

    public function __construct() {
        // Database connection parameters
        $this->host     = "localhost";
        $this->port     = 5432;
        $this->database = "workana";
        $this->user     = "postgres";
        $this->password = "123456";

        // Establish a database connection
        $this->connect();
    }

    public function connect() {
        // Build the connection string
        $connectionString = "host=$this->host port=$this->port dbname=$this->database user=$this->user password=$this->password";
        $this->conn  = pg_connect($connectionString); // Connect to the database
    }

    public function query($query, $params = []) {
        $result = pg_query_params($this->conn, $query, $params); // Execute a parameterized query
        return $result;
    }

    public function close() {
        if ($this->conn) {
            pg_close($this->conn); // Close the database connection
            $this->conn = null;
        }
    }

    public function insertRecord($table, $data) {
        $columns = implode(', ', array_keys($data)); // Get column names
        $placeholders = implode(', ', array_fill(0, count($data), '?')); // Create placeholders for values

        // Debugging output
        print_r($columns);
        print_r($placeholders);

        exit(); // Terminate script

        $values = array_values($data); // Get values
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)"; // Construct the INSERT query
        return $this->query($query, $values); // Execute the query
    }

    public function updateRecord($table, $data, $condition, $conditionParams = []) {
        $setClause = '';
        $values = array_values($data); // Get values

        // Construct the SET clause
        foreach (array_keys($data) as $column) {
            $setClause .= "$column = ?, ";
        }
        $setClause = rtrim($setClause, ', '); // Remove the trailing comma

        $query = "UPDATE $table SET $setClause WHERE $condition"; // Construct the UPDATE query
        $params = array_merge($values, $conditionParams); // Merge values and condition parameters
        return $this->query($query, $params); // Execute the query
    }

    public function deleteRecord($table, $condition, $conditionParams = []) {
        $query = "DELETE FROM $table WHERE $condition"; // Construct the DELETE query
        return $this->query($query, $conditionParams); // Execute the query
    }
}

?>