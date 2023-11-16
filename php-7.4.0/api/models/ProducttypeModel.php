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
        try {
            $query = "INSERT INTO producttype (name, percentValue) VALUES ($1, $2)";
            $result = $this->db->query($query, array($name, $percentValue));

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }

    public function updateProductType($id, $name, $percentValue) {
       try {
        $query = "UPDATE producttype SET name = $1, percentValue = $2 WHERE id = $3";
        $result = $this->db->query($query, array($name, $percentValue, $id));

        return $result;

    } catch (Exception $e) {
        return 'Houve uma falha, tente mais tarde.';
    }
}

    public function deleteProductType($id) {
        try {
            $query = "DELETE FROM producttype WHERE id = $1";
            $result = $this->db->query($query, array($id));

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }
}