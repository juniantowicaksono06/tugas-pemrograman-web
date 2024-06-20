<?php
    namespace User;
    use Controllers\Controller;
    use Models\MasterProvince;
    use Models\MasterCity;
    use Models\MasterMember;
    use Service\EmailService;
    class AuthController extends Controller {
    
        public function login() {
            // Jangan set layout pada login
            $this->setLayout(null);
            return $this->view("user/auth/login");
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
    }