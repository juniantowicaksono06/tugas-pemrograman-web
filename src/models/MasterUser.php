<?php
    require_once('./models/Model.php');
    class MasterUser extends Model {
        public function getUser(string $username, bool $active = false) {
            $sql = "SELECT * FROM master_user WHERE username = :username";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $user = $this->connection->fetchOne($sql, [':username'  => $username]);
            return $user;
        }

        public function getUserByUsernameOrEmail(string $username, string $email, $active = false) {
            $sql = "SELECT * FROM master_user WHERE (email = :email OR username = :username)";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $user = $this->connection->fetchOne($sql, [':email'  => $email, ':username' => $username]);
            return $user;
        }

        public function getUsers() {
            $user = $this->connection->fetchAll("SELECT * FROM master_user");
            return $user;
        }

        public function registerNewUser(array $data) {
            $user = $this->getUserByUsernameOrEmail($data['username'], $data['email'], true);
            if(empty($user)) {
                $id = UUIDv4();
                $this->connection->commands("INSERT INTO master_user(id, username, no_hp, email, fullname, password, user_type, user_status) VALUES(:id, :username, :no_hp, :email, :fullname, :password, :user_type, :user_status)", [
                    ':id'       => $id,
                    ':username' => $data['username'],
                    ':no_hp'    => $data['noHP'],
                    ':email'    => $data['email'],
                    ':fullname' => $data['fullname'],
                    ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                    ':user_type'=> 2,
                    ':user_status' => 0,
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
    }
?>