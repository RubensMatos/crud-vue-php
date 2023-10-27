<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER["REQUEST_METHOD"];

if ($method == 'OPTIONS') {

	header("HTTP/1.1 200 OK");
	exit();
}

$response = null;

require 'db.php';

if ($method === "GET") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$query = "SELECT id, name, amount, producttype_id FROM product ORDER BY name ASC";

		$result = $db->query($query);

		$data = pg_fetch_all($result);

		if($data){

			foreach ($data as &$row) {

				$row['amount'] = floatval($row['amount']);
				$row['producttype_id'] = intval($row['producttype_id']);
			}
		}

		echo json_encode($data);
	}
}

if ($method === "POST") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$requestBody = file_get_contents("php://input");

		$postData = json_decode($requestBody, true);

		$query = "INSERT INTO product (name, amount, producttype_id) VALUES ($1,$2,$3)";
		$result = pg_query_params($db->conn, $query, array($postData['name'],$postData['amount'],$postData['producttype_id']));

		if (!$result) {
			echo "error";
		}
	}
}

if ($method === "PUT") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$requestBody = file_get_contents("php://input");

		$postData = json_decode($requestBody, true);

		$params = array($postData['name'],$postData['amount'],$postData['id']);

		$query = "UPDATE product SET name = $1, amount = $2 WHERE id = $3";
		$result = pg_query_params($db->conn, $query, $params);

		if (!$result) {
			echo "error";
		}
	}
}

if ($method === "DELETE") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$requestBody = file_get_contents("php://input");

		$postData = json_decode($requestBody, true);

		$result = pg_query($db->conn, "DELETE FROM product WHERE id = ".$postData['id']);

		if (!$result) {
			echo "error";
		}
	}
}