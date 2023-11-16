<?php

class ComboModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getProductTypes() {
        $query = "SELECT id, name, percentvalue FROM producttype ORDER BY name ASC";
        $result = $this->db->query($query);

        return $result;
    }

    public function getProducts() {
        $query = "
            SELECT p.id, p.name, p.amount as value, p.producttype_id, pt.percentvalue as tax
            FROM product p
            LEFT JOIN producttype pt ON p.producttype_id = pt.id
            ORDER BY p.name ASC;
        ";

        $result = $this->db->query($query);

        return $result;
    }
}