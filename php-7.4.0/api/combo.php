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

		if (isset($_GET['productType'])) {

			$query = "SELECT id, name, percentvalue FROM producttype ORDER BY name ASC";

			$result = $db->query($query);

			$data = pg_fetch_all($result);

			if($data){

				foreach ($data as &$row) {

					$row['percentvalue'] = floatval($row['percentvalue']);
				}
			}

			echo json_encode($data);
		}

		if (isset($_GET['product'])) {

			$query = 

"

SELECT p.id, p.name, p.amount as value, p.producttype_id, pt.percentvalue as tax
FROM product p
LEFT JOIN producttype pt ON p.producttype_id = pt.id
ORDER BY p.name ASC;

";


			$result = $db->query($query);

			$data = pg_fetch_all($result);

			if($data){

				foreach ($data as &$row) {

					$row['value'] = floatval($row['value']);
					$row['tax']   = floatval($row['tax']);
				}
			}

			echo json_encode($data);
		}
	}
}