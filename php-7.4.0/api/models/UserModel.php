<?php

// models/UserModel.php

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function authenticateUser($username, $password) {
        $query = "SELECT * FROM users WHERE username = $1 AND password = $2";
        $params = array($username, $password);
        $result = $this->db->query($query, $params);

        if ($result !== false) {
            $row = (!empty($result)) ? $result[0] : null;

            if ($row) {
                // User authentication successful
                $data = array(1, intval($row['id']));
            } else {
                // Incorrect username or password
                $data = array(0, "Incorrect username or password.");
            }
        } else {
            // Database query failure
            $data = array(0, "An error occurred. Please try again later.");
        }

        return $data;
    }
}