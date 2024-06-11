<?php
    require_once('./models/Model.php');
    class MasterAuthor extends Model {
        private $tableName = "master_author";
        public function getAuthor(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }

        public function getAuthorByID(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $publisher;
        }

        public function getAuthors() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function createNewAuthor(array $data) {
            $author = $this->getAuthor($data['name']);
            if(empty($author)) {
                $this->connection->commands("INSERT INTO ". $this->tableName ." (id, name, status) 
                VALUES(:id, :name, :status)", [
                    ':id'          => UUIDv4(),
                    ':name'        => $data['name'],
                    ':status'      => 1,
                ]);
                return 1;
            }
            else {
                return 2;
            }
        }

        public function editAuthor(string $id, array $data) {
            $author = $this->getAuthorById($id);
            if(empty($author)) {
                return 2;
            }
            else {
                $author = $this->getAuthor($data['name']);
                if(!empty($author)) {
                    if($author['id'] != $id) {
                        return 3;
                    }
                }
                $this->connection->commands("UPDATE ". $this->tableName ." SET name = :name WHERE id = :id", [
                    ':name'        => $data['name'],
                    ':id'          => $id
                ]);
                return 1;
            }
        }

        public function deleteAuthor(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function activateAuthor(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }