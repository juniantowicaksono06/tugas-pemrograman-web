<?php
    namespace User;
    use Controllers\Controller;
    use Models\MasterProvince;
    use Models\MasterCity;
    use Models\MasterMember;
    use Utils\Session;
    use Service\EmailService;
    class AuthController extends Controller {
    
        public function login() {
            // Jangan set layout pada login
            $this->setLayout(null);
            return $this->view("user/auth/login");
        }

        public function logout() {
            $session = new Session();
            $session->destroy();
            return redirect("/auth/login");
        }

        public function resetPassword() {
            $this->setLayout(null);
            return $this->view("user/auth/resetpassword");
        }

        public function updateResetPassword(string $id) {
            $token = $_GET['token'];
            $masterMember = new MasterMember();
            $userAdmin = $masterMember->getUserByID($id);
            if(!empty($userAdmin) && !empty($token)) {
                $validUserToken = $userAdmin['valid_user_reset_token'];
                if($validUserToken !== null) {
                    $activationTokenTime = new \DateTime($validUserToken);
                    $currentDateTime = new \DateTime();
                    $sess = new Session();
                    if($userAdmin['user_reset_token'] == $token && $currentDateTime <= $activationTokenTime) {
                        $this->setLayout(null);
                        return $this->view("user/auth/updateresetpasword", [
                            'data'  => [
                                'email'       => $userAdmin['email'],
                                'id'          => $userAdmin['id'],
                                'reset_token' => $token
                            ]
                        ]);
                    }
                    $sess->setFlash('warning', 'Token tidak ditemukan');
                }
            }
            return redirect('/auth/login');
        }
    
        public function register() {
            $masterProvince = new MasterProvince();
            $masterCity = new MasterCity();
            $cities = $masterCity->getCities();
            $provinces = $masterProvince->getProvinces();
            $this->setLayout(null);
            return $this->view("user/auth/register", [
                'provinces'     => $provinces,
                'cities'        => $cities
            ]);
        }

        public function activate(string $id) {
            $token = $_GET['token'];
            $masterMember = new MasterMember();
            $userAdmin = $masterMember->getUserByID($id);
            if(!empty($userAdmin) && !empty($token)) {
                $validUserToken = $userAdmin['valid_user_activation_token'];
                if($validUserToken !== null) {
                    $activationTokenTime = new \DateTime($validUserToken);
                    $currentDateTime = new \DateTime();
                    $sess = new Session();
                    if($userAdmin['user_activation_token'] == $token && $currentDateTime <= $activationTokenTime) {
                        $update = $masterMember->activateUser($id);
                        if($update) {
                            $sess->setFlash('success', 'Berhasil aktivasi user, silahkan login!');
                        }
                    }
                    else {
                        $sess->setFlash('warning', 'Token tidak ditemukan atau invalid');
                    }
                }
            }
            return redirect('/auth/login');
        }

        public function actionRegister() {
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
                $uploadImage = imageUpload($_FILES['picture'], 'assets/image/user-profile-picture');
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
                ->setContent("<h3>Halo, ". $data['fullname'] ."</h3><br /><p>Selamat datang di PERPUS-KU.</p><p>Anda telah membuat akun untuk mengakses halaman dari PERPUS-KU.</p><p>Untuk mulai mengaksesnya anda bisa mulai dengan klik <a href='" . getBaseURL() . "/user/activate/".$data['id']."?token=".$activationToken."'>link berikut</a></p><br /><br /><p>Jika anda merasa tidak pernah melakukan permintaan ini mohon abaikan email ini</p></br /><br /><p>Terima Kasih</p>")
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
        
        public function actionLogin() {
            try {
                $dataValidate = [
                    'email'               => 'required|validEmail',
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
                $users = new MasterMember();
                $user = $users->getUser($data['email']);
                if(empty($user)) {
                    return jsonResponse(200, [
                        'code'      => 404,
                        'message'   => "User tidak ditemukan",
                        'error'     => []
                    ]);
                }

                if($user['user_status'] == 3) {
                    return jsonResponse(200, [
                        'code'      => 401,
                        'message'   => "User belum aktif, silahkan cek email anda",
                        'error'     => []
                    ]);
                }
                else if($user['user_status'] == 0) {
                    return jsonResponse(200, [
                        'code'      => 401,
                        'message'   => "User tidak aktif, silahkan hubungi administrator",
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
                    'id'            => $user['id'],
                    'fullname'      => $user['fullname'],
                    'gender'        => $user['gender'],
                    'email'         => $user['email'],
                    'no_hp'         => $user['no_hp'],
                    'user_status'   => $user['user_status'],
                    'birthdate'     => $user['birthdate'],
                    'birthplace'    => $user['birthplace'],
                    'alamat'        => $user['alamat'],
                    'picture'       => $user['picture']
                ];
                $session->set('user_credential', $session_data);
                return jsonResponse(200, [
                    'code'      => 200,
                    'message'   => "Berhasil login",
                    'data'      => $session_data,
                    'error'     => []
                ]);
            }
            catch(\Exception $e) {
                return jsonResponse(500, [
                    'code'      => 500,
                    'message'   => "Internal Server Error!",
                    'debugInfo' => $e->getMessage()
                ]);
            }
        }

        public function actionResetPassword() {
            try {
                $dataValidate = [
                    'email'           => 'required|validEmail'
                ];
                $data = $_POST;
                $this->validator->setInputName(array(
                    'email'              => "Email",
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
                $adminUser = $masterMember->getUserByEmail($data['email']);
                if($adminUser === false) {
                    return jsonResponse(200, [
                        'code'      => 404,
                        'message'   => "Email tidak ditemukan",
                    ]);
                }
                $resetPasswordToken = generateToken();
                $currentDate = new \DateTime();
                $currentDate->modify('+30 minutes');
                $dataToUpdate = [
                    'user_reset_token'      => $resetPasswordToken,
                    'valid_user_reset_token'=> $currentDate->format('Y-m-d H:i:s')
                ];
                $reset = $masterMember->resetPassword($data['email'], $dataToUpdate);
                if($reset !== 1) {
                    return jsonResponse(200, [
                        'code'      => 409,
                        'message'   => "Gagal reset password",
                    ]);
                }
                $mail = new EmailService();
                $mail->setAddress($data['email'], $adminUser['fullname'])
                ->setContent("<h3>Halo, ". $adminUser['fullname'] ."</h3><br /><p>Sepertinya anda ingin melakukan reset password anda.</p><p>Anda bisa melakukan reset password anda dengan klik link <a href='" . getBaseURL() . "/auth/update-reset-password/".$adminUser['id']."?token=".$resetPasswordToken."'>berikut</a></p><br /><p>Jika anda tidak merasa melakukan permintaan reset password jangan hiraukan email ini</p><br /><br /><p>Terima Kasih</p>")
                ->setSubject("Reset Password " . $adminUser['fullname'])
                ->send();
                return jsonResponse(200, [
                    'code'      => 200,
                    'message'   => "Kode token berhasil dikirim, silahkan cek email anda",
                ]);
            }
            catch(\Exception $e) {
                return jsonResponse(500, [
                    'code'      => 500,
                    'message'   => "Internal Server Error!",
                    'debugInfo' => $e->getMessage()
                ]);
            }
        }

        public function actionUpdateResetPassword(string $id) {
            $token = $_GET['token'];
            $dataValidate = [
                'password'           => 'required',
                'konfirmasiPassword' => 'required|matches[password]',
            ];
            $this->validator->setInputName(array(
                'password'           => "Password",
                'konfirmasiPassword' => 'Konfirmasi Password',
            ));
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
            $masterMember = new MasterMember();
            $userAdmin = $masterMember->getUserByID($id);
            
            if(!empty($userAdmin) && !empty($token)) {
                $validUserToken = $userAdmin['valid_user_reset_token'];
                $activationTokenTime = new \DateTime($validUserToken);
                $currentDateTime = new \DateTime();
                if($userAdmin['user_reset_token'] == $token && $currentDateTime <= $activationTokenTime) {
                    $update = $masterMember->updatePassword($id, password_hash($data['password'], PASSWORD_DEFAULT));
                    if($update === 1) {
                        return jsonResponse(200, [
                            'code'      => 200,
                            'message'   => "Berhasil melakukan reset password",
                        ]);
                    }
                }
                else {
                    return jsonResponse(200, [
                        'code'      => 404,
                        'message'   => "Token expired silahkan lakukan request reset password lagi",
                    ]);
                }
            }
            else {
                return jsonResponse(200, [
                    'code'      => 404,
                    'message'   => "Token atau ID tidak valid",
                ]);
            }
            return jsonResponse(200, [
                'code'      => 500,
                'message'   => "Gagal melakukan reset password",
            ]);
        }
    }