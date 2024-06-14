<?php 

class Connection {
    private $connection;

    public function __construct($dsn = null, $username = null, $password = null, $options = array()) {
        $env = $_ENV['ENV'];
        if(empty($dsn)) {
            $dsn = "mysql:host=".$env['DB_HOSTNAME'].";dbname=".$env['DB_NAME'].";port=".$env['DB_PORT'].";charset=utf8mb4";
        }
        if(empty($username)) {
            $username = $env['DB_USERNAME'];
        }
        if(empty($password)) {
            $password = $env['DB_PASSWORD'];
        }
        $this->createConnection( $dsn, $username, $password, $options );
    }

    public function createConnection($dsn, $username, $password, $options = array()) {
        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
            return $this;
        }
        catch(PDOException $e) {
            echo "<h1>Error: Connection to database error</h1>";
            die();
        }
    }

    public function fetchOne( $query, $values = array() ) {
        try {

            $stmt = $this->connection->prepare($query);
            if(is_associative_array($values)) {
                foreach( $values as $column => $value ) {
                    $stmt->bindValue($column, $value);
                }
                $stmt->execute();
            }
            else {
                $stmt->execute($values);
            }
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            return $results;
        }
        catch(PDOException $e) {
            echo "<h1>".$e->getMessage()."</h1>";
            die();
        }
    }

    public function fetchAll( $query, $values = array() ) {
        try {

            $stmt = $this->connection->prepare($query);
            if(is_associative_array($values)) {
                foreach( $values as $column => $value ) {
                    $stmt->bindValue($column, $value);
                }
                $stmt->execute();
            }
            else {
                $stmt->execute($values);
            }
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        catch(PDOException $e) {
            echo "<h1>".$e->getMessage()."</h1>";
            die();
        }
    }

    public function getLastInsertedId() {
        return $this->connection->lastInsertId();
    }

    public function commands($query, $bindings = array() ) {
        try {
            $stmt = $this->connection->prepare($query);
            foreach( $bindings as $column => $value ) {
                $stmt->bindValue($column, $value);
            }
            $stmt->execute();
        }
        catch(PDOException $e) {
            echo "<h1>".$e->getMessage()."</h1>";
            die();
        }
    }
}