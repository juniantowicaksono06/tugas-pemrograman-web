<?php
    require_once('./models/Model.php');
    class MasterUser extends Model {
        private $tableName = "master_user";
        public function getUser(string $username, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE username = :username";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $user = $this->connection->fetchOne($sql, [':username'  => $username]);
            return $user;
        }

        public function getUserByID(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $user = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $user;
        }

        public function getUserByUsernameOrEmail(string $username, string $email, bool $active = false, bool $fetchAll = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE (email = :email OR username = :username)";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            if($fetchAll) {
                $user = $this->connection->fetchAll($sql, [':email'  => $email, ':username' => $username]);    
            }
            else {
                $user = $this->connection->fetchOne($sql, [':email'  => $email, ':username' => $username]);
            }
            return $user;
        }

        public function getUsers() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function registerNewUser(array $data, int $userStatus = 0) {
            $user = $this->getUserByUsernameOrEmail($data['username'], $data['email'], true);
            if(empty($user)) {
                $id = UUIDv4();
                $this->connection->commands("INSERT INTO ". $this->tableName ." (id, username, no_hp, email, fullname, password, user_type, user_status) 
                VALUES(:id, :username, :no_hp, :email, :fullname, :password, :user_type, :user_status)", [
                    ':id'          => $id,
                    ':username'    => $data['username'],
                    ':no_hp'       => $data['noHP'],
                    ':email'       => $data['email'],
                    ':fullname'    => $data['fullname'],
                    ':password'    => password_hash($data['password'], PASSWORD_DEFAULT),
                    ':user_type'   => isset($data['userType']) ? strtolower($data['userType']) == 'admin' ? 1 : 2 : 2,
                    ':user_status' => $userStatus,
                ]);
                return 1;
            }
            else {
                if($user['email'] == $data['email']) {
                    return 2;
                }
                else if($user['username'] == $data['username']) {
                    return 3;
                }
            }
        }

        public function editUser(string $id, array $data) {
            $users = $this->getUserByUsernameOrEmail($data['username'], $data['email'], false, true);
            if(!empty($users)) {
                // Cek apakah email sudah dipakai oleh user lain
                foreach($users as $user) {
                    if($user['id'] != $id && $data['email'] == $user['email']) {
                        return 2;
                    }
                    // Cek apakah username sudah dipakai oleh user lain
                    else if($user['id'] != $id && $user['username'] == $data['username']) {
                        return 3;
                    }
                }
                $query = "UPDATE ". $this->tableName ." SET username = :username, 
                no_hp = :no_hp,
                email = :email,
                fullname = :fullname,
                user_type = :user_type";
                $parameter = [
                    ':id'       => $id,
                    ':username' => $data['username'],
                    ':no_hp'    => $data['noHP'],
                    ':email'    => $data['email'],
                    ':fullname' => $data['fullname'],
                    ':user_type'=> isset($data['userType']) ? strtolower($data['userType']) == 'admin' ? 1 : 2 : 2
                ];
                if(!empty($data['password'])) {
                    $query .= ", password = :password";
                    $parameter = array_merge($parameter, [':password'    => password_hash($data['password'], PASSWORD_DEFAULT)]);
                }
                $query .= " WHERE id = :id";
                $this->connection->commands($query, $parameter);
                return 1;
            }
            else {
                return 0;
            }
        }

        public function deleteUser(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET user_status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function activateUser(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET user_status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }