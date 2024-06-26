<?php
    namespace Models;
    class MasterPublisher extends Model {
        private $tableName = "master_publisher";
        public function getPublisher(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }

        public function getPublisherByID(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $publisher;
        }

        public function getPublishers() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function createNewPublisher(array $data) {
            $id = UUIDv4();
            $this->connection->commands("INSERT INTO ". $this->tableName ." (id, name, status) 
            VALUES(:id, :name, :status)", [
                ':id'          => $id,
                ':name'        => $data['name'],
                ':status'      => 1,
            ]);
            return 1;
        }

        public function editPublisher(string $id, array $data) {
            $publisher = $this->getPublisherById($id);
            if(empty($publisher)) {
                return 2;
            }
            else {
                $this->connection->commands("UPDATE ". $this->tableName ." SET name = :name WHERE id = :id", [
                    ':name'        => $data['name'],
                    ':id'          => $id
                ]);
                return 1;
            }
        }

        public function deletePublisher(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function activatePublisher(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }