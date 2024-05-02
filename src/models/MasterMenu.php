<?php
    require_once('./models/Model.php');
    class MasterMenu extends Model {
        private $tableName = "master_menu"; 
        public function getAdminParentMenu() {
            $sql = "SELECT id, name, link, is_parent, icon FROM ". $this->tableName ." WHERE menu_active = 1 AND menu_type = 1";
            $menu = $this->connection->fetchAll($sql);
            return $menu;
        }
    }