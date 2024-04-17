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

    public function logout() {
        $session = new Session();
        $session->destroy();
        return redirect("/login");
    }

    public function actionLogin() {
        try {
            $dataValidate = [
                'username'            => 'required|max:30',
                'password'            => 'required',
            ];
            $data = $_POST;
            $inputValid = $this->validator->validate($dataValidate, $data);
            if(!$inputValid) {
                return jsonResponse(200, [
                    'code'      => 400,
                    'message'   => "Bad Request",
                    'error'     => $this->validator->getMessages()
                ]);
            }
            $users = new MasterUser();
            $user = $users->getUser($data['username']);
            if(empty($user)) {
                return jsonResponse(200, [
                    'code'      => 404,
                    'message'   => "User tidak ditemukan",
                    'error'     => []
                ]);
            }

            if($user['user_status'] == 0) {
                return jsonResponse(200, [
                    'code'      => 401,
                    'message'   => "User belum diaktifkan",
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
                'fullname'      => $user['fullname'],
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

    public function actionRegister() {
        $dataValidate = [
            'fullname'           => 'required',
            'username'           => 'required|max:32',
            'email'              => 'required|validEmail',
            'password'           => 'required',
            'noHP'               => 'required|phoneNumber',
            'konfirmasiPassword' => 'required|matches[password]',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'username'           => "Username",
            'fullname'           => "Nama Lengkap",
            'email'              => "Email",
            'password'           => "Password",
            'konfirmasiPassword' => "Konfirmasi Password",
            'noHP'               => "Nomor HP",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $users = new MasterUser();
        $result = $users->registerNewUser($data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil Registrasi User",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => $result == 2 ? "Email sudah digunakan" : "Username sudah digunakan",
                'error'     => [],
            ]);
        }
    }
}