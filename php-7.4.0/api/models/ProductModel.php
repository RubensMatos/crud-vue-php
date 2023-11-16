<?php

class ProductModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllProducts() {
        $query = "SELECT id, name, amount, producttype_id FROM product ORDER BY name ASC";
        $result = $this->db->query($query);

        if ($result) {
            $data = $result;

            if ($data) {
                foreach ($data as &$row) {
                    $row['amount'] = floatval($row['amount']);
                    $row['producttype_id'] = intval($row['producttype_id']);
                }
            }

            return $data;
        } else {
            return null;
        }
    }

    public function addProduct($name, $amount, $producttype_id) {
        try {
            $query = "INSERT INTO product (name, amount, producttype_id) VALUES ($1, $2, $3)";
            $result = $this->db->query($query, array($name, $amount, $producttype_id));

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }

    public function updateProduct($id, $name, $amount) {
        try {
            $query = "UPDATE product SET name = $1, amount = $2 WHERE id = $3";
            $result = $this->db->query($query, array($name, $amount, $id));

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }

    public function deleteProduct($id) {
        try{
            $query = "DELETE FROM product WHERE id = $1";
            $result = $this->db->query($query, array($id));

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }
}