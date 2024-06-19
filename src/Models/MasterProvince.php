<?php
    namespace Models;
    class MasterProvince extends Model {
        private $tableName = "master_province";
        public function getProvince(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }
        public function getProvinces() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function createNewProvince(array $data) {
            $province = $this->getProvince($data['name']);
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


        public function editProvince(string $id, array $data) {
            $province = $this->getProvinceById($id);
            if(empty($province)) {
                return 2;
            }
            else {
                $province = $this->getProvince($data['name']);
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

        public function getProvinceById(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $publisher;
        }        

        public function deactivateProvince(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function reactivateProvince(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }