<?php
require_once './models/MasterUser.php';

class UserController extends Controller {
    public function users() {
        $user = new MasterUser();
        $data = $user->getUsers();
        return $this->view("admin/users/list", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Manajemen User"
            ],
            "data"  => $data
        ]);
    }

    public function create() {
        return $this->view("admin/users/create", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Tambah User"
            ],
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'fullname'           => 'required',
            'username'           => 'required|max:32',
            'email'              => 'required|validEmail',
            'password'           => 'required',
            'noHP'               => 'required|phoneNumber',
            'userType'           => 'required',
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
            'userType'           => 'required',
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