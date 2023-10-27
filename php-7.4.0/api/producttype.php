<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER["REQUEST_METHOD"];

if ($method == 'OPTIONS') {
    // Handle preflight OPTIONS request
    header("HTTP/1.1 200 OK");
    exit();
}

$response = null;

require 'db.php';

if ($method === "GET") {
    // Handle HTTP GET request
    $db = new Db();
    $db->connect();

    if ($db) {
        // Construct and execute a SELECT query
        $query = "SELECT id, name, percentvalue FROM producttype ORDER BY name ASC";
        $result = $db->query($query);

        $data = pg_fetch_all($result);

        if ($data) {
            // Convert 'percentvalue' to a float
            foreach ($data as &$row) {
                $row['percentvalue'] = floatval($row['percentvalue']);
            }
        }

        echo json_encode($data);
    }
}

if ($method === "POST") {
    // Handle HTTP POST request
    $db = new Db();
    $db->connect();

    if ($db) {
        // Retrieve the request body and decode JSON data
        $requestBody = file_get_contents("php://input");
        $postData = json_decode($requestBody, true);

        // Construct and execute an INSERT query
        $query = "INSERT INTO producttype (name, percentValue) VALUES ($1,$2)";
        $result = pg_query_params($db->conn, $query, array($postData['name'], $postData['percentvalue']));

        if (!$result) {
            echo "error";
        }
    }
}

if ($method === "PUT") {
    // Handle HTTP PUT request
    $db = new Db();
    $db->connect();

    if ($db) {
        // Retrieve the request body and decode JSON data
        $requestBody = file_get_contents("php://input");
        $postData = json_decode($requestBody, true);

        // Construct parameters for the UPDATE query
        $params = array($postData['name'], $postData['percentvalue'], $postData['id']);

        // Construct and execute an UPDATE query
        $query = "UPDATE producttype SET name = $1, percentValue = $2 WHERE id = $3";
        $result = pg_query_params($db->conn, $query, $params);

        if (!$result) {
            echo "error";
        }
    }
}

if ($method === "DELETE") {
    // Handle HTTP DELETE request
    $db = new Db();
    $db->connect();

    if ($db) {
        // Retrieve the request body and decode JSON data
        $requestBody = file_get_contents("php://input");
        $postData = json_decode($requestBody, true);

        // Construct and execute a DELETE query
        $result = pg_query($db->conn, "DELETE FROM producttype WHERE id = " . $postData['id']);

        if (!$result) {
            echo "error";
        }
    }
}
?>