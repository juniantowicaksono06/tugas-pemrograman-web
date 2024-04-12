<?php
require_once('./config/database.php');

class Model {
    protected $connection;

    public function __construct($dsn = null, $username = null, $password = null, $options = array()) {
        $this->setConnection($dsn, $username, $password, $options);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function setConnection($dsn = null, $username = null, $password = null, $options = array()) {
        $this->connection = new Connection($dsn, $username, $password, $options);
    }
}