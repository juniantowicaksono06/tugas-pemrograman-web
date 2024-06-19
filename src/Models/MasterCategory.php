<?php
    namespace Models;
    class MasterCategory extends Model {
        private $tableName = "master_category";
        public function getCategory(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }
        public function getCategories() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function createNewCategory(array $data) {
            $province = $this->getCategory($data['name']);
            if(empty($province)) {
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

        public function editCategory(string $id, array $data) {
            $province = $this->getCategoryById($id);
            if(empty($province)) {
                return 2;
            }
            else {
                $province = $this->getCategory($data['name']);
                if(!empty($province)) {
                    if($province['id'] != $id) {
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

        public function getCategoryById(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $publisher;
        }        

        public function deactivateCategory(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function reactivateCategory(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }