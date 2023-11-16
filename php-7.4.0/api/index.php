<?php

// Configurações para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responde com sucesso (200 OK) e encerra a execução
    header("HTTP/1.1 200 OK");
    exit();
}

// Inclui o arquivo index.php na pasta public
include __DIR__ . '/public/index.php';