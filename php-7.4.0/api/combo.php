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
        // Check if the request is for product types
		if (isset($_GET['productType'])) {
            // Construct and execute a SELECT query for product types
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

        // Check if the request is for products
		if (isset($_GET['product'])) {
            // Construct and execute a SELECT query for products with a LEFT JOIN to product types
			$query = "
			SELECT p.id, p.name, p.amount as value, p.producttype_id, pt.percentvalue as tax
			FROM product p
			LEFT JOIN producttype pt ON p.producttype_id = pt.id
			ORDER BY p.name ASC;
			";

			$result = $db->query($query);

			$data = pg_fetch_all($result);

			if ($data) {
                // Convert 'value' and 'tax' to floats
				foreach ($data as &$row) {
					$row['value'] = floatval($row['value']);
					$row['tax'] = floatval($row['tax']);
				}
			}

			echo json_encode($data);
		}
	}
}