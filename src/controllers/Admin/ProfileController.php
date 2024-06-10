<?php
require_once './models/MasterAdmin.php';
require_once './service/EmailService.php';
class ProfileController extends Controller {
    public function editProfile() {
        // Jangan set layout pada login
        // $this->setLayout('admin_default');
        return $this->view("admin/profile/editprofile", [
            'page'  => [
                "title"     => "Edit Profil"
            ]
        ]);
    }

    public function actionEdit($id) {
        $dataValidate = [
            'fullname'           => 'required',
            'email'              => 'required|validEmail',
            'password'           => 'optional',
            'noHP'               => 'required|phoneNumber',
            'konfirmasiPassword' => 'optional|matches[password]',
        ];
        // $this->_parsePut();
        // $data = $GLOBALS['_PUT'];
        $data = $_POST;
        $this->validator->setInputName(array(
            'fullname'           => "Nama Lengkap",
            'email'              => "Email",
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
        $masterAdmin = new MasterAdmin();
        $adminUser = $masterAdmin->getUserByID($id);
        if(empty($adminUser)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "User tidak ditemukan",
                'error'     => [],
            ]);
        }
        $picture = $adminUser['picture'];
        $dataToUpdate = [
            'fullname'  => $data['fullname'],
            'no_hp'     => $data['noHP']
        ];
        if(!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $dataToUpdate['password'] = $hashedPassword;
        }
        // Upload image
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
        // Cek apakah email berubah
        if($data['email'] != $adminUser['email']) {
            $users = $masterAdmin->getUserByUsernameOrEmail($adminUser['username'], $data['email'], false, true);
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
            $currentDate = new DateTime();

            // Print the current date and time
            // echo "Current Date and Time: " . $currentDate->format('Y-m-d H:i:s') . "\n";
        
            // Add 30 minutes to the current date and time
            $currentDate->modify('+30 minutes');
            $tokenValidDate = $currentDate->format('Y-m-d H:i:s');
            $dataToUpdate['user_activation_token'] = $changeEmailToken;
            $dataToUpdate['valid_user_activation_token'] = $tokenValidDate;
            $mail = new EmailService();
            $mail->setAddress($dataToUpdate['new_email'], $dataToUpdate['fullname'])
            ->setContent("<h3>Halo, ". $dataToUpdate['fullname'] ."</h3><br /><p>Sepertinya anda ingin melakukan pergantian email.</p><p>Anda bisa mengganti email anda dengan klik link <a href='" . getBaseURL() . "/admin/profile/change-email/".$id."?token=".$changeEmailToken."'>berikut</a></p><br /><p>Jika anda tidak merasa melakukan pergantian email jangan hiraukan email ini</p><br /><br /><p>Terima Kasih</p>")
            ->setSubject("Change Email");
            $mail->send();
        }
        if(array_key_exists('picture', $dataToUpdate)) {
            if(strpos($adminUser['picture'], 'default.png') === false) {
                unlink($adminUser['picture']);
            }
        }
        $result = $masterAdmin->editUserAdmin($id, $dataToUpdate);
        if($result === 1) {
            $session = new Session();
            $session_data = [
                'id'            => $adminUser['id'],
                'username'      => $adminUser['username'],
                'fullname'      => $dataToUpdate['fullname'],
                'email'         => $adminUser['email'],
                'no_hp'         => $dataToUpdate['no_hp'],
                'user_status'   => $adminUser['user_status'],
                'picture'       => $picture
            ];
            $session->set('user_credential', $session_data);
            return jsonResponse(200, [
                'code'      => $data['email'] != $adminUser['email'] ? 201 : 200,
                'message'   => $data['email'] != $adminUser['email'] ? "Berhasil ubah data silahkan cek email anda untuk konfirmasi perubahan email" : "Berhasil ubah data profil",
                'error'     => []
            ]);
        }
        return jsonResponse(500, [
            'code'      => 500,
            'message'   => "Gagal edit profil",
            'error'     => []
        ]);
    }

    public function actionEditEmail($id) {
        $token = $_GET['token'];
        $adminUser = new MasterAdmin();
        $user = $adminUser->getUserByID($id);
        if(!empty($user) && !empty($token)) {
            $validUserToken = $user['valid_user_activation_token'];
            $userActivationToken = $user['user_activation_token'];
            if(!empty($validUserToken) && !empty($userActivationToken)) {
                $activationTokenTime = new DateTime($validUserToken);
                $currentDateTime = new DateTime();
                $sess = new Session();
                if ($currentDateTime <= $activationTokenTime && $token == $userActivationToken) {
                    $update = $adminUser->updateEmail($id, $user['new_email']);
                    if($update === 1) {
                        $sess->setFlash('success', "Berhasil mengubah email");
                        $session_data = [
                            'id'            => $user['id'],
                            'username'      => $user['username'],
                            'fullname'      => $user['fullname'],
                            'email'         => $user['new_email'],
                            'no_hp'         => $user['no_hp'],
                            'user_status'   => $user['user_status'],
                            'picture'       => $user['picture']
                        ];
                        $sess->set('user_credential', $session_data);
                    } 
                }
                else {
                    $sess->setFlash('warning', "Token tidak valid");
                }
            }
        }
        return redirect('/admin');
    }
}