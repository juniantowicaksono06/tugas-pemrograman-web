<?php 
namespace Admin;
use Controllers\Controller;
use Models\MasterMember;
use Models\MasterProvince;
use Models\MasterCity;
use Service\EmailService;
use Utils\Session;

class MemberController extends Controller {
    public function member() {
        $masterMember = new MasterMember();
        $data = $masterMember->getMembers();
        return $this->view("admin/member/list", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Manajamen Anggota"
            ],
            "data"  => $data
        ]);
    }

    public function detail(string $id) {
        $masterMember = new MasterMember();
        $data = $masterMember->getUserByID($id);
        
        return $this->view("admin/member/detail", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Detail Anggota"
            ],
            "data"  => $data
        ]);
    }    
    
    public function create() {
        $masterProvince = new MasterProvince();
        $masterCity = new MasterCity();
        $cities = $masterCity->getCities();
        $provinces = $masterProvince->getProvinces();
        // $this->setLayout(null);
        return $this->view("admin/member/create", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Tambah Anggota"
            ],
            'provinces'     => $provinces,
            'cities'        => $cities
        ]);
    }
    
    public function edit($id) {
        $masterProvince = new MasterProvince();
        $masterMember = new MasterMember();
        $masterCity = new MasterCity();
        $cities = $masterCity->getCities();
        $provinces = $masterProvince->getProvinces();
        $dataUser = $masterMember->getUserByID($id);
        // $this->setLayout(null);
        return $this->view("admin/member/edit", [
            "page"  => [
                "parent"    => "User",
                "title"     => "Edit Anggota"
            ],
            'provinces'     => $provinces,
            'cities'        => $cities,
            'data'          => $dataUser
        ]);
    }
    
    // DELETE METHOD
    public function actionDeactivate(string $id) {
        $user = new MasterMember();
        $user->deactivateUser($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan anggota",
            'error'     => [],
        ]);
    }

    public function actionReActivate(string $id) {
        $users = new MasterMember();
        $user = $users->getUserByID($id);
        if(empty($user)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Anggota tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($user)) {
            if($user['user_status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Anggota sudah aktif',
                    'error'     => []
                ]);
            }
            $users->activateUser($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Anggota berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }

    public function actionCreate() {
        $dataValidate = [
            'fullname'           => 'required',
            'gender'             => 'required',
            'birthplace'         => 'required',
            'birthdate'          => 'required|validDate',
            'address'            => 'required|max:1024',
            'email'              => 'required|validEmail',
            'noHP'               => 'required|phoneNumber',
            'password'           => 'required',
            'konfirmasiPassword' => 'required|matches[password]',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'fullname'           => "Nama Lengkap",
            'gender'             => "Jenis Kelamin",
            'birthplace'         => "Tempat Lahir",
            'birthdate'          => "Tanggal Lahir",
            'address'            => "Alamat",
            'email'              => "Email",
            'noHP'               => "Nomor Handphone",
            'password'           => "Password",
            'konfirmasiPassword' => "Konfirmasi Password",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $picture = null;
        if(!empty($_FILES['picture'])) {
            $uploadImage = imageUpload($_FILES['picture'], 'assets/image/user-profile-picture/');
            if($uploadImage['status'] != 1) {
                return jsonResponse(200, [
                    'code'      => 400,
                    'message'   => "Upload image gagal",
                    'error'     => $uploadImage['message']
                ]);
            }
            $data['picture'] = $uploadImage['uploadedFile'];
            $picture = $uploadImage['uploadedFile'];
        }
        else {
            $data['picture'] = "assets/image/user-profile-picture/default.png";
        }
        $users = new MasterMember();
        $activationToken = generateToken();
        $currentDate = new \DateTime();
        $currentDate->modify('+30 minutes');
        $data['user_activation_token'] = $activationToken;
        $data['valid_user_activation_token'] = $currentDate->format('Y-m-d H:i:s');
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['id'] = UUIDv4();
        $result = $users->createUser($data);
        if($result == 1) {
            $mail = new EmailService();
            $mail->setAddress($data['email'], $data['fullname'])
            ->setContent("<h3>Halo, ". $data['fullname'] ."</h3><br /><p>Selamat datang di PERPUS-KU.</p><p>Anda telah dibuatkan akun oleh ". $_SESSION['admin_credential']['fullname'] ." untuk mengakses halaman dari PERPUS-KU.</p><p>Untuk mulai mengaksesnya anda bisa mulai dengan klik <a href='" . getBaseURL() . "/user/activate/".$data['id']."?token=".$activationToken."'>link berikut</a></p><br /><br /><p>Jika anda merasa tidak pernah melakukan permintaan ini mohon abaikan email ini</p></br /><br /><p>Terima Kasih</p>")
            ->setSubject("Aktivasi User ".$data['fullname']);
            $mail->send();
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil Registrasi User, Silahkan cek email anda",
                'error'     => [],
            ]);
        }
        else {
            if($picture !== null) {
                unlink($picture);
            }
            if($result == 0) {
                return jsonResponse(200, [
                    'code'      => 500,
                    'message'   => "Telah terjadi kesalahan",
                    'error'     => [],
                ]);    
            }
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => $result == 2 ? "Email sudah digunakan" : "Username sudah digunakan",
                'error'     => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $dataValidate = [
            'fullname'           => 'required',
            'gender'             => 'required',
            'birthplace'         => 'required',
            'birthdate'          => 'required|validDate',
            'email'              => 'required|validEmail',
            'password'           => 'optional',
            'noHP'               => 'required|phoneNumber',
            'address'             => 'required',
            'konfirmasiPassword' => 'optional|matches[password]',
        ];
        // $this->_parsePut();
        // $data = $GLOBALS['_PUT'];
        $data = $_POST;
        $this->validator->setInputName(array(
            'fullname'           => "Nama Lengkap",
            'gender'             => "Jenis Kelamin",
            'birthplace'         => "Tempat Lahir",
            'birthdate'          => "Tanggal Lahir",
            'email'              => "Email",
            'password'           => "Password",
            'address'            => 'Alamat',
            'konfirmasiPassword' => "Konfirmasi Password",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $masterMember = new MasterMember();
        $regularUser = $masterMember->getUserByID($id);
        if(empty($regularUser)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "User tidak ditemukan",
                'error'     => [],
            ]);
        }
        $picture = $regularUser['picture'];
        $dataToUpdate = [
            'fullname'  => $data['fullname'],
            'no_hp'     => $data['noHP'],
            'gender'    => $data['gender'],
            'birthdate' => $data['birthdate'],
            'birthplace'=> $data['birthplace'],
            'alamat'    => $data['address']
        ];
        if(!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $dataToUpdate['password'] = $hashedPassword;
        }
        // Upload image
        if(!empty($_FILES['picture'])) {
            $uploadImage = imageUpload($_FILES['picture'], "assets/image/user-profile-picture/");
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
        // Cek apakah email berubah
        if($data['email'] != $regularUser['email']) {
            $users = $masterMember->getUsersByEmail($data['email']);
            if(!empty($users)) {
                $emailTaken = false;
                // Cek apakah email sudah digunakan oleh user lain?
                foreach($users as $user) {
                    if($user['id'] != $id && $data['email'] == $user['email']) {
                        $emailTaken = true;
                        break;
                    }
                }
                if($emailTaken) {
                    return jsonResponse(200, [
                        'code'      => 409,
                        'message'   => "Email " . $data['email'] . "Telah digunakan",
                        'error'     => []
                    ]);
                }
            }
            $dataToUpdate['new_email'] = $data['email'];
            $changeEmailToken = generateToken();
            $currentDate = new \DateTime();

            // Print the current date and time
            // echo "Current Date and Time: " . $currentDate->format('Y-m-d H:i:s') . "\n";
        
            // Add 30 minutes to the current date and time
            $currentDate->modify('+30 minutes');
            $tokenValidDate = $currentDate->format('Y-m-d H:i:s');
            $dataToUpdate['user_activation_token'] = $changeEmailToken;
            $dataToUpdate['valid_user_activation_token'] = $tokenValidDate;
            $mail = new EmailService();
            $mail->setAddress($dataToUpdate['new_email'], $dataToUpdate['fullname'])
            ->setContent("<h3>Halo, ". $dataToUpdate['fullname'] ."</h3><br /><p>User ".$_SESSION['admin_credential']['fullname']." telah melakukan pergantian email.</p><p>Anda bisa mengganti email anda dengan klik link <a href='" . getBaseURL() . "/profile/change-email/".$id."?token=".$changeEmailToken."'>berikut</a></p><br /><p>Jika anda tidak merasa melakukan pergantian email jangan hiraukan email ini</p><br /><br /><p>Terima Kasih</p>")
            ->setSubject("Change Email");
            $mail->send();
        }
        if(array_key_exists('picture', $dataToUpdate)) {
            if(strpos($regularUser['picture'], 'default.png') === false) {
                unlink($regularUser['picture']);
            }
        }
        $result = $masterMember->editUser($id, $dataToUpdate);
        if($result === 1) {
            $session = new Session();
            $session_data = [
                'id'            => $regularUser['id'],
                'fullname'      => $dataToUpdate['fullname'],
                'gender'        => $data['gender'],
                'birthdate'     => $data['birthdate'],
                'birthplace'    => $data['birthplace'],
                'email'         => $regularUser['email'],
                'no_hp'         => $dataToUpdate['no_hp'],
                'alamat'        => $data['address'],
                'user_status'   => $regularUser['user_status'],
                'picture'       => $picture
            ];
            $session->set('user_credential', $session_data);
            return jsonResponse(200, [
                'code'      => $data['email'] != $regularUser['email'] ? 201 : 200,
                'message'   => $data['email'] != $regularUser['email'] ? "Berhasil ubah data silahkan cek email anda untuk konfirmasi perubahan email" : "Berhasil ubah data profil",
                'error'     => []
            ]);
        }
        return jsonResponse(500, [
            'code'      => 500,
            'message'   => "Gagal edit profil",
            'error'     => []
        ]);
    }
}