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
        $query = "SELECT id, customer, product_data FROM \"order\" ORDER BY id DESC";
        $result = $db->query($query);

        $data = pg_fetch_all($result);

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

        $productData = [];

        foreach ($postData as $product) {
            $name = $product['name'];
            $quantity = (int) $product['quantity'];
            $value = (float) $product['value'];
            $tax = (float) $product['tax'];
            $valueUnit = (float) $product['valueUnit'];

            $productData[] = [
                'name' => $name,
                'quantity' => $quantity,
                'value' => $value,
                'tax' => $tax,
                'valueUnit' => $valueUnit,
            ];
        }

        $orderData = [
            'customer' => 'João',
            'product_data' => json_encode($productData),
        ];

        // Construct and execute an INSERT query
        $query = "INSERT INTO \"order\" (customer, product_data) VALUES ($1, $2)";
        $params = [$orderData['customer'], $orderData['product_data']];

        $result = pg_query_params($db->conn, $query, $params);

        if ($result) {
            echo "Order inserted successfully.";
        } else {
            echo "Error inserting order: " . pg_last_error($db->conn);
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
        $result = pg_query($db->conn, "DELETE FROM \"order\" WHERE id = " . $postData['id']);

        if (!$result) {
            echo "error";
        }
    }
}