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

if ($method === "POST") {
    // Handle HTTP POST request
    $db = new Db();
    $db->connect();

    if ($db) {
        // Retrieve the request body and decode JSON data
        $requestBody = file_get_contents("php://input");
        $postData = json_decode($requestBody, true);

        // Get the username and password from the POST data
        $username = isset($postData['username']) ? $postData['username'] : null;
        $password = isset($postData['password']) ? $postData['password'] : null;

        // Construct and execute a SELECT query to check user credentials
        $query = "SELECT * FROM users WHERE username = $1 AND password = $2";
        $params = array($username, $password);
        $result = $db->query($query, $params);

        if ($result) {
            $row = pg_fetch_assoc($result);

            if ($row) {
                // User authentication successful
                $data = array(1, intval($row['id']));
            } else {
                // Incorrect username or password
                $data = array(0, "Incorrect username or password.");
            }
        } else {
            // Database query failure
            $data = array(0, "An error occurred. Please try again later.");
        }

    } else {
        // Database connection failure
        $data = array(0, "Unable to connect to the database.");
    }
}

header("Content-Type: application/json");

echo json_encode($data);

?>