<?php

class ProductController {
    private $productModel;

    public function __construct($productModel) {
        $this->productModel = $productModel;
    }

    public function handle() {
        $method = $_SERVER["REQUEST_METHOD"];

        // Handle preflight OPTIONS request
        if ($method == 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }

        // Handle HTTP requests based on the method
        switch ($method) {
            case "GET":
                $data = $this->productModel->getAllProducts();
                echo json_encode($data);
                break;
            case "POST":
                $requestBody = file_get_contents("php://input");
                $postData = json_decode($requestBody, true);
                $result = $this->productModel->addProduct($postData['name'], $postData['amount'], $postData['producttype_id']);
                echo json_encode($result);
                break;
            case "PUT":
                $requestBody = file_get_contents("php://input");
                $postData = json_decode($requestBody, true);
                $result = $this->productModel->updateProduct($postData['id'], $postData['name'], $postData['amount']);
                echo json_encode($result);
                break;
            case "DELETE":
                $requestBody = file_get_contents("php://input");
                $postData = json_decode($requestBody, true);
                $result = $this->productModel->deleteProduct($postData['id']);
                echo json_encode($result);
                break;
            default:
                // Unsupported method
                header("HTTP/1.1 405 Method Not Allowed");
                echo json_encode(['error' => 'Method not allowed']);
                break;
        }
    }
}
