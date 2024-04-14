<?php
require_once './models/MasterUser.php';
class AuthController extends Controller {
    public function register() {
        // Jangan set layout pada register
        $this->setLayout(null);
        return $this->view("auth/register");
    }
    
    public function login() {
        // Jangan set layout pada login
        $this->setLayout(null);
        return $this->view("auth/login");
    }

    public function actionLogin() {
        try {
            $dataValdate = [
                'username'            => 'required|min:4|max:30',
                'password'            => 'required',
            ];
            $data = $_POST;
            $inputValid = $this->validator->validate($dataValdate, $data);
            if(!$inputValid) {
                return jsonResponse(200, [
                    'code'      => 400,
                    'message'   => "Bad Request",
                    'error'     => $this->validator->getMessages()
                ]);
            }
            $users = new MasterUser();
            $user = $users->getActveUser($data['username']);
            if(empty($user)) {
                return jsonResponse(200, [
                    'code'      => 404,
                    'message'   => "User tidak ditemukan",
                    'error'     => []
                ]);
            }

            if(!password_verify($data['password'], $user['password'])) {
                return jsonResponse(200, [
                    'code'      => 404,
                    'message'   => "Username atau password salah",
                    'error'     => []
                ]);
            }
            $session = new Session();
            $session_data = [
                'username'      => $user['username'],
                'user_type'     => $user['user_type'],
                'user_status'   => $user['user_status']
            ];
            $session->set('user_credential', $session_data);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => "Berhasil login",
                'data'      => $session_data,
                'error'     => []
            ]);
        }
        catch(Exception $e) {
            return jsonResponse(500, [
                'code'      => 500,
                'message'   => "Internal Server Error!",
                'debugInfo' => $e->getMessage()
            ]);
        }
    }
}