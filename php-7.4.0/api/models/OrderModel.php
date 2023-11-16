<?php

class OrderModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllOrders() {
        $query = "SELECT id, customer, product_data FROM \"order\" ORDER BY id DESC";
        $result = $this->db->query($query);

        return $result;
    }

    public function addOrder($customer, $productData) {
        try{
            $query = "INSERT INTO \"order\" (customer, product_data) VALUES ($1, $2)";
            $result = $this->db->query($query, [$customer, $productData]);

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }

    public function deleteOrder($id) {
        try{
            $query = "DELETE FROM \"order\" WHERE id = $1";
            $result = $this->db->query($query, [$id]);

            return $result;

        } catch (Exception $e) {
            return 'Houve uma falha, tente mais tarde.';
        }
    }
}