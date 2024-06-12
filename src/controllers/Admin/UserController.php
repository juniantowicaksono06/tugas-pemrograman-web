<?php
require_once './models/MasterAdmin.php';
require_once './service/EmailService.php';

class UserController extends Controller {
    public function users() {
        $user = new MasterAdmin();
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
        $user = new MasterAdmin();
        $data = $user->getUserByID($id);
        return $this->view("admin/users/edit", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Edit User"
            ],
            "data"  => $data
        ]);
    }

    public function activate(string $id) {
        $token = $_GET['token'];
        $masterAdmin = new MasterAdmin();
        $userAdmin = $masterAdmin->getUserByID($id);
        if(!empty($userAdmin) && !empty($token)) {
            $validUserToken = $userAdmin['valid_user_activation_token'];
            if($validUserToken !== null) {
                $activationTokenTime = new DateTime($validUserToken);
                $currentDateTime = new DateTime();
                $sess = new Session();
                if($userAdmin['user_activation_token'] == $token && $currentDateTime <= $activationTokenTime) {
                    $update = $masterAdmin->activateUser($id);
                    if($update) {
                        $sess->setFlash('success', 'Berhasil aktivasi user, silahkan login!');
                    }
                }
                else {
                    $sess->setFlash('warning', 'Token tidak ditemukan atau invalid');
                }
            }
        }
        return redirect('/admin/auth/login');
    }

    // POST METHOD
    public function actionCreate() {
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
        $dataToUpdate = [
            'fullname'        => $data['fullname'],
            'no_hp'           => $data['noHP'],
            'username'        => $data['username'],
            'email'           => $data['email'],
            'user_status'     => 3,
        ];
        $picture = null;
        if(!empty($_FILES['picture'])) {
            $uploadImage = imageUpload($_FILES['picture']);
            if($uploadImage['status'] != 1) {
                return jsonResponse(200, [
                    'code'      => 400,
                    'message'   => "Upload image gagal",
                    'error'     => $uploadImage['message'], 
                ]);
            }
            $dataToUpdate['picture'] = $uploadImage['uploadedFile'];
            $picture = $uploadImage['uploadedFile'];
        }
        else {
            $dataToUpdate['picture'] = 'assets/image/admin-profile-picture/default.png';
        }
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $dataToUpdate['password'] = $hashedPassword;
        
        $activationToken = generateToken();
        $currentDate = new DateTime();
        $currentDate->modify('+30 minutes');
        $tokenValidDate = $currentDate->format('Y-m-d H:i:s');
        $dataToUpdate['id'] = UUIDv4();
        $dataToUpdate['user_activation_token'] = $activationToken;
        $dataToUpdate['valid_user_activation_token'] = $tokenValidDate;
        $users = new MasterAdmin();
        $result = $users->insertAdminUser($dataToUpdate);

        if($result == 1) {
            $mail = new EmailService();
            $mail->setAddress($dataToUpdate['email'], $dataToUpdate['fullname'])
            ->setContent("<h3>Halo, ". $dataToUpdate['fullname'] ."</h3><br /><p>Selamat datang di PERPUS-KU.</p><p>User ". $_SESSION['admin_credential']['fullname'] ." telah membuatkan anda akun untuk mengakses halaman admin dari PERPUS-KU.</p><p>Untuk mulai mengaksesnya anda bisa mulai dengan klik <a href='" . getBaseURL() . "/admin/users/activate/".$dataToUpdate['id']."?token=".$activationToken."'>link berikut</a></p><br /><br /><br /><p>Terima Kasih</p>")
            ->setSubject("Aktivasi User ".$dataToUpdate['fullname']);
            $mail->send();
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil Tambah User",
                'error'     => [],
            ]);
        }
        else {
            if($picture !== null) {
                unlink($picture);
            }
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => $result == 2 ? "Email sudah digunakan oleh user lain" : "Username sudah digunakan oleh user lain",
                'error'     => [],
            ]);
        }
    }
    
    // DELETE METHOD
    public function actionDeactivate(string $id) {
        $user = new MasterAdmin();
        $user->deactivateUser($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan user",
            'error'     => [],
        ]);
    }

    // PUT METHOD
    public function actionEdit(string $id) {
        $dataValidate = [
            'fullname'           => 'required',
            'username'           => 'required|max:32',
            'email'              => 'required|validEmail',
            'noHP'               => 'required|phoneNumber',
            'userType'           => 'required',
            'konfirmasiPassword' => 'matches[password]',
        ];
        $this->_parsePut();
        $data = $GLOBALS['_PUT'];
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $users = new MasterAdmin();
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
    
    // POST METHOD
    public function actionReActivate(string $id) {
        $users = new MasterAdmin();
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