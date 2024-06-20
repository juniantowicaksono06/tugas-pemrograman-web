<?php
    namespace Models;
    class MasterCity extends Model {
        private $tableName = "master_city";
        private $referencedOn = "master_province";
        public function getCity(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }
        public function getCities() {
            $cities = $this->connection->fetchAll("SELECT c.name AS city_name, p.name AS province_name, c.id AS id, c.id_province as id_province, c.created_at AS created_at, c.status as status FROM ". $this->tableName ." c LEFT JOIN ". $this->referencedOn ." p ON p.id = c.id_province");
            return $cities;
        }

        public function createNewCity(array $data) {
            $city = $this->getCity($data['name']);
            $query = "SELECT * FROM ".$this->referencedOn." WHERE id = :id";
            $province = $this->connection->fetchOne($query, [
                ':id'   => $data['id_province']
            ]);
            if(empty($province)) {
                return 3;
            }
            if(empty($city)) {
                $this->connection->commands("INSERT INTO ". $this->tableName ." (id, id_province, name, status) 
                VALUES(:id, :id_province, :name, :status)", [
                    ':id'           => UUIDv4(),
                    ':id_province'  => $data['id_province'],
                    ':name'         => $data['name'],
                    ':status'       => 1,
                ]);
                return 1;
            }
            else {
                return 2;
            }
        }


        public function editCity(string $id, array $data) {
            $city = $this->getCityById($id);
            $query = "SELECT * FROM ".$this->referencedOn." WHERE id = :id";
            $province = $this->connection->fetchOne($query, [
                ':id'   => $data['id_province']
            ]);
            if(empty($province)) {
                return 3;
            }
            if(empty($city)) {
                return 2;
            }
            else {
                $city = $this->getCity($data['name']);
                if(!empty($city)) {
                    if($city['id'] != $id) {
                        return 4;
                    }
                }
                $this->connection->commands("UPDATE ". $this->tableName ." SET name = :name, id_province = :id_province WHERE id = :id", [
                    ':name'         => $data['name'],
                    ':id_province'  => $data['id_province'],
                    ':id'           => $id
                ]);
                return 1;
            }
        }

        public function getCityById(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $publisher;
        }        

        public function deactivateCity(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function reactivateCity(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }