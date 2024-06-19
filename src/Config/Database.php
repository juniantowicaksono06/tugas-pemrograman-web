<?php 
namespace Config;
class Database {
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
            $this->connection = new \PDO($dsn, $username, $password, $options);
            return $this;
        }
        catch(\PDOException $e) {
            return $this;
        }
    }

    public function beginTransaction() {
        try {
            if(!$this->connection->inTransaction()) {
                $this->connection->beginTransaction();
            }
            return $this;
        } catch (\PDOException $e) {
            return $this;
        }
    }

    public function rollBack() {
        try {
            if($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            return $this;
        } catch (\PDOException $e) {
            return $this;
        }
    }

    public function commit() {
        try {
            if($this->connection->inTransaction()) {
                $this->connection->commit();
            }
            return $this;
        } catch (\PDOException $e) {
            return $this;
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
            $results = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $results;
        }
        catch(\PDOException $e) {
            return [];
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
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $results;
        }
        catch(\PDOException $e) {
            return [];
        }
    }

    public function getLastInsertedId() {
        return $this->connection->lastInsertId();
    }

    public function batchInsert($table, $rows, $columns = array()) {
        try {
            if (empty($rows)) {
                return true;
            }
            $query = "INSERT INTO " . $table;
            $columnCount = !empty($columns) ? count($columns) : count(reset($rows));
            $query .= !empty($columns) ? '('.implode(', ', $columns).')' : '';
            $rowPlaceholder = ' ('.implode(', ', array_fill(1, $columnCount, '?')).')';
            $query .= " VALUES" . implode(', ', array_fill(1, count($rows), $rowPlaceholder));
            $stmt = $this->connection->prepare($query);
            $data = [];
            foreach($rows as $rowData) {
                $data = array_merge($data, array_values($rowData));
            }
            // var_dump($query);exit;
            return $stmt->execute($data);
        }
        catch(\PDOException $e) {
            return false;
        }

    }

    public function commands($query, $bindings = array() ) {
        try {
            $stmt = $this->connection->prepare($query);
            foreach( $bindings as $column => $value ) {
                $stmt->bindValue($column, $value);
            }
            return $stmt->execute();
        }
        catch(\PDOException $e) {
            return false;
        }
    }
}