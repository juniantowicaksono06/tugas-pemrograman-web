<?php
    require_once('./models/Model.php');
    class MasterUser extends Model {
        public function getActveUser(string $username) {
            $user = $this->connection->fetchOne("SELECT * FROM master_user WHERE username = :username AND user_status = 1", [':username'  => $username]);
            return $user;
        }

        public function getUsers() {
            $user = $this->connection->fetchAll("SELECT * FROM master_user");
            return $user;
        }

        public function registerNewUser(array $data) {
            $users = $this->getActveUser($data['username']);
            if(empty($users)) {
                $id = UUIDv4();
                $this->connection->commands("INSERT INTO master_user(id, username, no_hp, email, fullname, password, user_type, user_status) VALUES(:id, :username, :no_hp, :email, :fullname, :password, :user_type, :user_status)", [
                    ':id'       => $id,
                    ':username' => $data['username'],
                    ':no_hp'    => $data['no_hp'],
                    ':email'    => $data['email'],
                    ':fullname' => $data['fullname'],
                    ':password' => password_hash($data['username'], PASSWORD_DEFAULT),
                    ':user_type'=> 2,
                    ':user_status' => 0,
                ]);
            }
        }
    }
?>