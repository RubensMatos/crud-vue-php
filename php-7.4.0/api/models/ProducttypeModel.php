<?php

// models/ProducttypeModel.php

class ProducttypeModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllProductTypes() {
        $query = "SELECT id, name, percentvalue FROM producttype ORDER BY name ASC";
        $result = $this->db->query($query);

        return $result;
    }

    public function addProductType($name, $percentValue) {
        $query = "INSERT INTO producttype (name, percentValue) VALUES ($1, $2)";
        $result = $this->db->query($query, array($name, $percentValue));

        if ($result) {
            return 'success';
        } else {
            echo "Error: " . pg_last_error($this->db->conn);
            return 'error';
        }
    }

    public function updateProductType($id, $name, $percentValue) {
        $query = "UPDATE producttype SET name = $1, percentValue = $2 WHERE id = $3";
        $result = $this->db->query($query, array($name, $percentValue, $id));

        if ($result) {
            return 'success';
        } else {
            echo "Error: " . pg_last_error($this->db->conn);
            return 'error';
        }
    }

    public function deleteProductType($id) {
        $query = "DELETE FROM producttype WHERE id = $1";
        $result = $this->db->query($query, array($id));

        if ($result) {
            return 'success';
        } else {
            echo "Error: " . pg_last_error($this->db->conn);
            return 'error';
        }
    }
}