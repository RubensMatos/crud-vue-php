<?php

header("Content-Type: application/json; charset=UTF-8");

// Simulação de um banco de dados (substitua com seu banco de dados real)
$items = array(
    1 => array("id" => 1, "name" => "Item 1"),
    2 => array("id" => 2, "name" => "Item 2"),
    3 => array("id" => 3, "name" => "Item 3"),
);

// Função para obter todos os itens
function getAllItems() {
    global $items;
    return $items;
}

// Função para obter um item por ID
function getItemById($id) {
    global $items;
    return isset($items[$id]) ? $items[$id] : null;
}

// Função para adicionar um novo item
function addItem($data) {
    global $items;
    $newId = max(array_keys($items)) + 1;
    $data["id"] = $newId;
    $items[$newId] = $data;
    return $data;
}

// Função para atualizar um item existente
function updateItem($id, $data) {
    global $items;
    if (isset($items[$id])) {
        $items[$id] = $data;
        return $data;
    }
    return null;
}

// Função para excluir um item por ID
function deleteItem($id) {
    global $items;
    if (isset($items[$id])) {
        $deletedItem = $items[$id];
        unset($items[$id]);
        return $deletedItem;
    }
    return null;
}

// Verifique o método da solicitação
$method = $_SERVER["REQUEST_METHOD"];

// Manipule as solicitações
if ($method === "GET") {
    $response = getAllItems();
} elseif ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $response = addItem($data);
} elseif ($method === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    $itemId = $_GET["id"];
    $response = updateItem($itemId, $data);
} elseif ($method === "DELETE") {
    $itemId = $_GET["id"];
    $response = deleteItem($itemId);
}

if ($response === null) {
    http_response_code(404);
    $response = array("message" => "Recurso não encontrado.");
} else {
    http_response_code(200);
}

echo json_encode($response);