<?php
namespace User;
use Controllers\Controller;
use Models\MasterProvince;
use Models\MasterCity;
use Models\MasterMember;
use Service\EmailService;
use Utils\Session;

class ProfileController extends Controller {
    public function editProfile() {
        $masterProvince = new MasterProvince();
        $masterCity = new MasterCity();
        $cities = $masterCity->getCities();
        $provinces = $masterProvince->getProvinces();
        // Jangan set layout pada login
        $this->setLayout('user_layout');
        return $this->view("user/profile/editprofile", [
            'page'  => [
                "title"     => "Edit Profil"
            ],
            'provinces'     => $provinces,
            'cities'        => $cities
        ]);
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
            ->setContent("<h3>Halo, ". $dataToUpdate['fullname'] ."</h3><br /><p>Sepertinya anda ingin melakukan pergantian email.</p><p>Anda bisa mengganti email anda dengan klik link <a href='" . getBaseURL() . "/profile/change-email/".$id."?token=".$changeEmailToken."'>berikut</a></p><br /><p>Jika anda tidak merasa melakukan pergantian email jangan hiraukan email ini</p><br /><br /><p>Terima Kasih</p>")
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

    public function editEmail($id) {
        $token = $_GET['token'];
        $adminUser = new MasterMember();
        $user = $adminUser->getUserByID($id);
        if(!empty($user) && !empty($token)) {
            $validUserToken = $user['valid_user_activation_token'];
            $userActivationToken = $user['user_activation_token'];
            if(!empty($validUserToken) && !empty($userActivationToken)) {
                $activationTokenTime = new \DateTime($validUserToken);
                $currentDateTime = new \DateTime();
                $sess = new Session();
                if ($currentDateTime <= $activationTokenTime && $token == $userActivationToken) {
                    $update = $adminUser->updateEmail($id, $user['new_email']);
                    if($update === 1) {
                        $sess->setFlash('success', "Berhasil mengubah email");
                        if(!empty($sess->get('user_credential'))) {
                            $session_data = [
                                'id'            => $user['id'],
                                'fullname'      => $user['fullname'],
                                'gender'        => $user['gender'],
                                'email'         => $user['new_email'],
                                'no_hp'         => $user['no_hp'],
                                'user_status'   => $user['user_status'],
                                'birthdate'     => $user['birthdate'],
                                'birthplace'    => $user['birthplace'],
                                'alamat'        => $user['alamat'],
                                'picture'       => $user['picture']
                            ];
                            $sess->set('user_credential', $session_data);
                        }
                    } 
                }
                else {
                    $sess->setFlash('warning', "Token tidak valid");
                }
            }
        }
        return redirect('/');
    }
}