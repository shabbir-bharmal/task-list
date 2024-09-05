<?php
require_once '../config/config.php';

class Model
{
    protected $db;

    public function __construct()
    {
        global $pdo; // Use the global $pdo variable
        $this->db = $pdo;
    }
}