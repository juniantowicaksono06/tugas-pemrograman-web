<?php
    namespace Models;
    class MasterMember extends Model {
        private $tableName = "master_member";
        public function getUser(string $email, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE email = :email";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $user = $this->connection->fetchOne($sql, [':email'  => $email]);
            return $user;
        }

        public function getUserByID(string $id) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            $user = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $user;
        }

        public function getUserByEmail(string $email) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE email = :email";
            $user = $this->connection->fetchOne($sql, [':email'  => $email]);
            if(empty($user)) {
                return false;
            }
            return $user;
        }

        public function getUsersByEmail(string $email) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE email = :email";
            $user = $this->connection->fetchAll($sql, [':email'  => $email]);
            if(empty($user)) {
                return false;
            }
            return $user;
        }

        public function activateUser(string $id) {
            try {
                $query = "UPDATE " . $this->tableName . " SET user_status = 1, valid_user_activation_token = null, user_activation_token = null WHERE id = :id";
                $this->connection->commands($query, [
                    ':id'   => $id
                ]);
                return 1;
            }
            catch(\Exception $e) {
                return 2;
            }
        }
        

        public function getTotalMember() {
            $member = $this->connection->fetchOne("SELECT COUNT(id) AS total_member FROM " . $this->tableName . " WHERE user_status = 1");
            return $member;
        }
        public function getActiveMembers() {
            $members = $this->connection->fetchAll("SELECT * FROM " . $this->tableName . " WHERE user_status = 1");
            return $members;
        }

        public function resetPassword(string $email, array $data) {
            try {
                $query = "UPDATE ". $this->tableName ." SET ";
                $i = 0;
                foreach($data as $key => $value) {
                    $query .= $key . " = " . ":" . $key;
                    if($i < count($data) - 1) {
                        $query .= ", ";
                    }
                    $i++;
                }
                $query .= " WHERE email = :email";
                $data[':email'] = $email;
                $this->connection->commands($query, $data);
                return 1;
            }
            catch(\Exception $e) {
                return 2;
            }
        }
        

        public function updatePassword(string $id, string $password) {
            try {
                $query = "UPDATE ". $this->tableName ." SET password = :password, user_reset_token = null, valid_user_reset_token = null";
                $query .= " WHERE id = :id";
                $this->connection->commands($query, [
                    ':password' => $password,
                    ':id'       => $id
                ]);
                return 1;
            }
            catch(\Exception $e) {
                return 2;
            }
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

        public function createUser(array $data) {
            $user = $this->getUserByEmail($data['email']);
            if(empty($user)) {
                $insert = $this->connection->commands("INSERT INTO ". $this->tableName ." (id, no_hp, email, gender, birthplace, birthdate, alamat, fullname, password, user_status, user_activation_token, valid_user_activation_token, picture) 
                VALUES(:id, :no_hp, :email, :gender, :birthplace, :birthdate, :alamat, :fullname, :password, :user_status, :user_activation_token, :valid_user_activation_token, :picture)", [
                    ':id'                           => $data['id'],
                    ':no_hp'                        => $data['noHP'],
                    ':email'                        => $data['email'],
                    ':gender'                       => $data['gender'],
                    ':birthplace'                   => $data['birthplace'],
                    ':birthdate'                    => $data['birthdate'],
                    ':alamat'                       => $data['address'],
                    ':fullname'                     => $data['fullname'],
                    ':password'                     => $data['password'],
                    ':user_status'                  => 2,
                    ':user_activation_token'        => $data['user_activation_token'],
                    ':valid_user_activation_token'  => $data['valid_user_activation_token'],
                    ':picture'                      => $data['picture']
                ]);
                if($insert !== false) {
                    return 1;
                }
            }
            else {
                if($user['email'] == $data['email']) {
                    return 2;
                }
                else if($user['username'] == $data['username']) {
                    return 3;
                }
            }
            return 0;
        }

        public function updateEmail(string $id, string $email) {
            try {
                $sql = "UPDATE ". $this->tableName ." SET email = :email, user_activation_token = null, valid_user_activation_token = null, new_email = null WHERE id = :id";
                $this->connection->commands($sql, [
                    ':email'    => $email,
                    ':id'       => $id
                ]);
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }

        public function registerNewUser(array $data, int $userStatus = 0) {
            $user = $this->getUserByUsernameOrEmail($data['username'], $data['email'], true);
            if(empty($user)) {
                $this->connection->commands("INSERT INTO ". $this->tableName ." (username, no_hp, email, fullname, password, user_type, user_status) 
                VALUES(:username, :no_hp, :email, :fullname, :password, :user_type, :user_status)", [
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
            try {
                $query = "UPDATE ". $this->tableName ." SET ";
                $i = 0;
                foreach($data as $key => $value) {
                    $query .= $key . " = " . ":" . $key;
                    if($i < count($data) - 1) {
                        $query .= ", ";
                    }
                    $i++;
                }
                $query .= " WHERE id = :id";
                $data[':id'] = $id;
                $this->connection->commands($query, $data);
                return 1;
            } catch (\Exception $e) {
                return 2;
            }
        }

        // public function editUser(string $id, array $data) {
        //     $users = $this->getUserByUsernameOrEmail($data['username'], $data['email'], false, true);
        //     if(!empty($users)) {
        //         // Cek apakah email sudah dipakai oleh user lain
        //         foreach($users as $user) {
        //             if($user['id'] != $id && $data['email'] == $user['email']) {
        //                 return 2;
        //             }
        //             // Cek apakah username sudah dipakai oleh user lain
        //             else if($user['id'] != $id && $user['username'] == $data['username']) {
        //                 return 3;
        //             }
        //         }
        //         $query = "UPDATE ". $this->tableName ." SET username = :username, 
        //         no_hp = :no_hp,
        //         email = :email,
        //         fullname = :fullname,
        //         user_type = :user_type";
        //         $parameter = [
        //             ':id'       => $id,
        //             ':username' => $data['username'],
        //             ':no_hp'    => $data['noHP'],
        //             ':email'    => $data['email'],
        //             ':fullname' => $data['fullname'],
        //             ':user_type'=> isset($data['userType']) ? strtolower($data['userType']) == 'admin' ? 1 : 2 : 2
        //         ];
        //         if(!empty($data['password'])) {
        //             $query .= ", password = :password";
        //             $parameter = array_merge($parameter, [':password'    => password_hash($data['password'], PASSWORD_DEFAULT)]);
        //         }
        //         $query .= " WHERE id = :id";
        //         $this->connection->commands($query, $parameter);
        //         return 1;
        //     }
        //     else {
        //         return 0;
        //     }
        // }

        public function deactivateUser(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET user_status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }