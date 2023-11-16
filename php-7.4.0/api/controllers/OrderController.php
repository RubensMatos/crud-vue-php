<?php

class OrderController {
    private $orderModel;

    public function __construct($orderModel) {
        $this->orderModel = $orderModel;
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
            $data = $this->orderModel->getAllOrders();
            echo json_encode($data);
            break;
            case "POST":
            $requestBody = file_get_contents("php://input");
            $postData = json_decode($requestBody, true);

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

            $result = $this->orderModel->addOrder($orderData['customer'], $orderData['product_data']);
            echo json_encode($result);
            break;
            case "DELETE":
            $requestBody = file_get_contents("php://input");
            $postData = json_decode($requestBody, true);
            $result = $this->orderModel->deleteOrder($postData['id']);
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