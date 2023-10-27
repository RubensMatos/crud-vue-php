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

		$query = "SELECT id, customer, product_data FROM \"order\" ORDER BY id DESC";

		$result = $db->query($query);

		$data = pg_fetch_all($result);

		echo json_encode($data);
	}
}

if ($method === "POST") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$requestBody = file_get_contents("php://input");

		$postData = json_decode($requestBody, true);

		/* print_r($postData);

		$products = [
			['Produto A', 4, 40, 0.2, 10],
			['Produto B', 5, 100, 1.1, 20]
		];

		print_r($products);

		exit(); */

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

		$query = "INSERT INTO \"order\" (customer, product_data) VALUES ($1, $2)";
		$params = [$orderData['customer'], $orderData['product_data']];

		$result = pg_query_params($db->conn, $query, $params);

		if ($result) {
			echo "Pedido inserido com sucesso.";
		} else {
			echo "Erro ao inserir pedido: " . pg_last_error($db->conn);
		}
	}
}

if ($method === "DELETE") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$requestBody = file_get_contents("php://input");

		$postData = json_decode($requestBody, true);

		$result = pg_query($db->conn, "DELETE FROM \"order\" WHERE id = ".$postData['id']);

		if (!$result) {
			echo "error";
		}
	}
}