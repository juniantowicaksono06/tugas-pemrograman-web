<?php
    require_once('./models/Model.php');
    class Procurement extends Model {
        private $tableName = "procurement";

        public function getProcurements() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }
    }