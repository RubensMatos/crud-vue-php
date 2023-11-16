<?php

// controllers/AuthController.php

class AuthController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function handle() {
        
        $requestBody = file_get_contents("php://input");
        $postData = json_decode($requestBody, true);

        $username = isset($postData['username']) ? $postData['username'] : null;
        $password = isset($postData['password']) ? $postData['password'] : null;

        // Utilize o modelo para autenticar o usuário
        $result = $this->userModel->authenticateUser($username, $password);

        echo json_encode($result);
    }
}