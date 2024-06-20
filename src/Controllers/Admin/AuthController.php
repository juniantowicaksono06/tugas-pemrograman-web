<?php
namespace Admin;
use Controllers\Controller;
use Models\MasterAdmin;
use Service\EmailService;
use Utils\Session;

class AuthController extends Controller {
    
    public function login() {
        // Jangan set layout pada login
        $this->setLayout(null);
        return $this->view("admin/auth/login");
    }

    public function logout() {
        $session = new Session();
        $session->destroy();
        return redirect("/admin/auth/login");
    }

    public function resetPassword() {
        $this->setLayout(null);
        return $this->view("admin/auth/resetpassword");
    }

    public function updateResetPassword(string $id) {
        $token = $_GET['token'];
        $masterAdmin = new MasterAdmin();
        $userAdmin = $masterAdmin->getUserByID($id);
        if(!empty($userAdmin) && !empty($token)) {
            $validUserToken = $userAdmin['valid_user_reset_token'];
            if($validUserToken !== null) {
                $activationTokenTime = new \DateTime($validUserToken);
                $currentDateTime = new \DateTime();
                $sess = new Session();
                if($userAdmin['user_reset_token'] == $token && $currentDateTime <= $activationTokenTime) {
                    $this->setLayout(null);
                    return $this->view("admin/auth/updateresetpasword", [
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
        return redirect('/admin/auth/login');
    }

    public function actionUpdateResetPassword(string $id) {
        $token = $_GET['token'];
        $dataValidate = [
            'password'           => 'required',
            'konfirmasiPassword' => 'required|matches[password]',
        ];
        $this->validator->setInputName(array(
            'password'           => "Nama Penerbit",
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
        $masterAdmin = new MasterAdmin();
        $userAdmin = $masterAdmin->getUserByID($id);
        
        if(!empty($userAdmin) && !empty($token)) {
            $validUserToken = $userAdmin['valid_user_reset_token'];
            $activationTokenTime = new \DateTime($validUserToken);
            $currentDateTime = new \DateTime();
            if($userAdmin['user_reset_token'] == $token && $currentDateTime <= $activationTokenTime) {
                $update = $masterAdmin->updatePassword($id, password_hash($data['password'], PASSWORD_DEFAULT));
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
            $masterAdmin = new MasterAdmin();
            $adminUser = $masterAdmin->getUserByEmail($data['email']);
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
            $reset = $masterAdmin->resetPassword($data['email'], $dataToUpdate);
            if($reset !== 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => "Gagal reset password",
                ]);
            }
            $mail = new EmailService();
            $mail->setAddress($data['email'], $adminUser['fullname'])
            ->setContent("<h3>Halo, ". $adminUser['fullname'] ."</h3><br /><p>Sepertinya anda ingin melakukan reset password anda.</p><p>Anda bisa melakukan reset password anda dengan klik link <a href='" . getBaseURL() . "/admin/profile/update-reset-password/".$adminUser['id']."?token=".$resetPasswordToken."'>berikut</a></p><br /><p>Jika anda tidak merasa melakukan permintaan reset password jangan hiraukan email ini</p><br /><br /><p>Terima Kasih</p>")
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
            $users = new MasterAdmin();
            $user = $users->getUser($data['username']);
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
                'username'      => $user['username'],
                'fullname'      => $user['fullname'],
                'email'         => $user['email'],
                'no_hp'         => $user['no_hp'],
                'user_status'   => $user['user_status'],
                'picture'       => $user['picture']
            ];
            $session->set('admin_credential', $session_data);
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

    // public function actionRegister() {
    //     $dataValidate = [
    //         'fullname'           => 'required',
    //         'username'           => 'required|max:32',
    //         'email'              => 'required|validEmail',
    //         'password'           => 'required',
    //         'noHP'               => 'required|phoneNumber',
    //         'konfirmasiPassword' => 'required|matches[password]',
    //     ];
    //     $data = $_POST;
    //     $this->validator->setInputName(array(
    //         'username'           => "Username",
    //         'fullname'           => "Nama Lengkap",
    //         'email'              => "Email",
    //         'password'           => "Password",
    //         'konfirmasiPassword' => "Konfirmasi Password",
    //         'noHP'               => "Nomor HP",
    //     ));
    //     $inputValid = $this->validator->validate($dataValidate, $data);
    //     if(!$inputValid) {
    //         return jsonResponse(200, [
    //             'code'      => 400,
    //             'message'   => "Bad Request",
    //             'error'     => $this->validator->getMessages()
    //         ]);
    //     }
    //     $uploadImage = imageUpload($_FILES['picture']);
    //     if($uploadImage['status'] != 1) {
    //         return jsonResponse(200, [
    //             'code'      => 400,
    //             'message'   => "Upload image gagal",
    //             'error'     => $uploadImage['message']
    //         ]);
    //     }
    //     $data['picture'] = $uploadImage['uploadedFile'];
    //     $users = new MasterAdmin();
    //     $result = $users->insertAdminUser($data);
    //     if($result == 1) {
    //         return jsonResponse(200, [
    //             'code'      => 201,
    //             'message'   => "Berhasil Registrasi User",
    //             'error'     => [],
    //         ]);
    //     }
    //     else {
    //         return jsonResponse(200, [
    //             'code'      => 409,
    //             'message'   => $result == 2 ? "Email sudah digunakan" : "Username sudah digunakan",
    //             'error'     => [],
    //         ]);
    //     }
    // }

    // public function tes() {
    //     // $servers = array(
    //     //     array("ssl://www.google.com", 443),
    //     //     array("smtp.gmail.com", 465),
    //     //     array("smtp.gmail.com", 587),
    //     // );
        
    //     // foreach ($servers as $server) {
    //     //     list($server, $port) = $server;
    //     //     echo "<h1>Attempting connect to <tt>$server:$port</tt></h1>\n";
    //     //     flush();
    //     //     $socket = fsockopen($server, $port, $errno, $errstr, 10);
    //     //     if(!$socket) {
    //     //       echo "<p>ERROR: $server:$port - $errstr ($errno)</p>\n";
    //     //     } else {
    //     //       echo "<p>SUCCESS: $server:$port - ok</p>\n";
    //     //     }
    //     //     flush();
    //     // }
    //     $mail = new EmailService();
    //     $mail->setAddress("juniantowicaksono06@gmail.com", "Junianto Ichwan Dwi Wicaksono")->setContent("<h1>Hello This is a Test</h1>")->setSubject("Sending Email With PHPMailer");
    //     $send = $mail->send();
    //     $mail->close();
    //     if($send) {
    //         return jsonResponse(200, [
    //             'code'      => 200,
    //             'message'   => "Email sent successfully",
    //             'error'     => [],
    //         ]);
    //     }
        
    //     return jsonResponse(200, [
    //         'code'      => 404,
    //         'message'   => "Fail to send email",
    //         'error'     => [],
    //     ]);
    // }
}