<?php
    require_once('./models/Model.php');
    class MasterSubMenu extends Model {
        private $tableName = "master_sub_menu";
        public function getAdminSubMenu() {
            $sql = "SELECT id, name, link, icon, id_menu FROM ". $this->tableName ." WHERE menu_active = 1 AND menu_type = 1";
            $menu = $this->connection->fetchAll($sql);
            return $menu;
        }
    }