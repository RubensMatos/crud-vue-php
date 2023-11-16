<?php

// CORS settings
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Respond with success (200 OK) and exit execution
    header("HTTP/1.1 200 OK");
    exit();
}

// Include the index.php file in the public folder
include __DIR__ . '/public/index.php';