<?php

// Set headers for cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER["REQUEST_METHOD"];

// Handle OPTIONS request (preflight)
if ($method == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

$response = null;

require 'db.php'; // Include the database connection code

if ($method === "GET") {
    // Handle HTTP GET request
    $db = new Db();
    $db->connect();

    if ($db) {
        // Construct and execute a SELECT query
        $query = "SELECT id, name, amount, producttype_id FROM product ORDER BY name ASC";
        $result = $db->query($query);

        $data = pg_fetch_all($result);

        if ($data) {
            // Convert 'amount' to a float and 'producttype_id' to an integer
            foreach ($data as &$row) {
                $row['amount'] = floatval($row['amount']);
                $row['producttype_id'] = intval($row['producttype_id']);
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
        $query = "INSERT INTO product (name, amount, producttype_id) VALUES ($1, $2, $3)";
        $result = pg_query_params($db->conn, $query, array($postData['name'], $postData['amount'], $postData['producttype_id']));

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

        $params = array($postData['name'], $postData['amount'], $postData['id']);

        // Construct and execute an UPDATE query
        $query = "UPDATE product SET name = $1, amount = $2 WHERE id = $3";
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
        $result = pg_query($db->conn, "DELETE FROM product WHERE id = " . $postData['id']);

        if (!$result) {
            echo "error";
        }
    }
}