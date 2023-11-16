<?php

class ComboController {
    private $comboModel;

    public function __construct($comboModel) {
        $this->comboModel = $comboModel;
    }

    public function handle() {
        $method = $_SERVER["REQUEST_METHOD"];

        // Handle preflight OPTIONS request
        if ($method == 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }

        // Handle HTTP GET request
        if ($method === "GET") {
            // Check if the request is for product types
            if (isset($_GET['productType'])) {
                $data = $this->comboModel->getProductTypes();
                echo json_encode($data);
            }

            // Check if the request is for products
            if (isset($_GET['product'])) {
                $data = $this->comboModel->getProducts();
                echo json_encode($data);
            }
        }
    }
}