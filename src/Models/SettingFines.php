<?php

namespace Models;

class SettingFines extends Model {
    private $tableName = "setting_fine";
    public function getFines() {
        
        $fines = $this->connection->fetchOne("SELECT denda  FROM ". $this->tableName);
        if(empty($fines)) {
            $fines = [
                'denda'     => 1000,
            ];
        }
        return $fines;
    }
    public function updateFines($data) {
        $fines = $this->connection->fetchOne("SELECT denda  FROM ". $this->tableName);
        $id = UUIDv4();
        if(empty($fines)) {
            $update = $this->connection->commands('INSERT INTO ' . $this->tableName . " (id, denda) VALUES(:id, :denda)", [
                ':id'           => $id,
                ':denda'        => $data['denda']
            ]);
        }
        else {
            $update = $this->connection->commands('UPDATE ' . $this->tableName . " SET denda = :denda", [
                ':denda'        => $data['denda']
            ]);
        }
        if($update === false) {
            return 2;
        }
        return 1;
    }
}