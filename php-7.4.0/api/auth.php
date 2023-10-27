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

if ($method === "POST") {

	$db = new Db();
	$db->connect();

	if ($db) {

		$requestBody = file_get_contents("php://input");

		$postData = json_decode($requestBody, true);

		$username = isset($postData['username']) ? $postData['username'] : null;
		$password = isset($postData['password']) ? $postData['password'] : null;

    	$query = "SELECT * FROM users WHERE username = $1 AND password = $2";

		$params = array($username, $password);

		$result = $db->query($query, $params);

		if ($result) {
        	$row = pg_fetch_assoc($result);

			if ($row) {

				$data = array(1,intval($row['id']));

			} else {
        		$data = array(0,"Nome de usuário ou senha incorretos.");
			}
		} else {
        	$data = array(0,"Houve uma falha. Tente mais tarde.");
		}

	} else {
	    $data = array(0,"Não foi possível conectar ao banco de dados.");
	}
}

header("Content-Type: application/json");

echo json_encode($data);

?>