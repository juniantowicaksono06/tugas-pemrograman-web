<?php
namespace Models;
use Config\Database;
class Model {
    protected $connection;

    public function __construct($dsn = null, $username = null, $password = null, $options = array()) {
        $this->setConnection($dsn, $username, $password, $options);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function setConnection($dsn = null, $username = null, $password = null, $options = array()) {
        $this->connection = new Database($dsn, $username, $password, $options);
    }
}