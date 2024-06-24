<?php
    namespace Models;
    class MasterMenuUser extends Model {
        private $tableName = "master_menu_user"; 
        public function getMenu() {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE status = 1 AND is_parent = 1 ORDER BY `order` ASC";
            $menu = $this->connection->fetchAll($sql);
            return $menu;
        }

        

        public function getSubMenu() {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE status = 1 AND is_parent = 1 AND has_child = 1";
            $menus = $this->connection->fetchAll($sql);
            $data = [];
            foreach($menus as $menu) {
                $sql = "SELECT * FROM ". $this->tableName ." WHERE status = 1 AND is_parent = 0 AND has_child = 0 AND parent_id = :parent_id ORDER BY `order` ASC";
                $subMenu = $this->connection->fetchAll($sql, [
                    ':parent_id'    => $menu['id']
                ]);
                $data[$menu['id']]   = $subMenu;
            }
            return $data;
        }
    }