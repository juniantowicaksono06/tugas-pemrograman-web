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
    
    public function edit(string $id) {
        $user = new MasterUser();
        $data = $user->getUserByID($id);
        return $this->view("admin/users/edit", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Edit User"
            ],
            "data"  => $data
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
        $result = $users->registerNewUser($data, 1);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil Tambah User",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => $result == 2 ? "Email sudah digunakan oleh user lain" : "Username sudah digunakan oleh user lain",
                'error'     => [],
            ]);
        }
    }

    public function actionDelete(string $id) {
        $user = new MasterUser();
        $user->deleteUser($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menghapus user",
            'error'     => [],
        ]);
    }

    public function actionEdit(string $id) {
        $dataValidate = [
            'fullname'           => 'required',
            'username'           => 'required|max:32',
            'email'              => 'required|validEmail',
            'noHP'               => 'required|phoneNumber',
            'userType'           => 'required',
            'konfirmasiPassword' => 'matches[password]',
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
        $result = $users->editUser($id, $data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => "Berhasil Update Data User",
                'error'     => [],
            ]);
        }
        else {
            switch ($result) {
                case 2:
                    $msg = "Email sudah digunakan oleh user lain";
                    break;
                case 3:
                    $msg = "Username sudah digunakan oleh user lain";
                    break;
                case 4:
                    $msg = "User tidak ditemukan";
                    break;
                default:
                    $msg = "";
                    break;
            }
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => $msg,
                'error'     => [],
            ]);
        }
    }
    
    public function actionActivate(string $id) {
        $users = new MasterUser();
        $user = $users->getUserByID($id);
        if(empty($user)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'User tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($user)) {
            if($user['user_status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'User sudah aktif',
                    'error'     => []
                ]);
            }
            $users->activateUser($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'User berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}