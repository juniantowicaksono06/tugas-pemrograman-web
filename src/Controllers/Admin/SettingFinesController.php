<?php 
namespace Admin;
use Controllers\Controller;
use Models\SettingFines;
class SettingFinesController extends Controller {
    public function fines() {
        $settingFines = new SettingFines();
        $fines = $settingFines->getFines();
        return $this->view("admin/setting-fines/update", [
            "page"  => [
                "parent"    => "Pengaturan",
                "title"     => "Pengaturan Denda"
            ],
            "fines"  => $fines
        ]);
    }

    public function actionUpdate() {
        $dataValidate = [
            'denda'           => 'required|validJson',
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
        $settingFines = new SettingFines();
        $update = $settingFines->updateFines($data);
        if($update === 1) {
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => "Berhasil mengatur denda",
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 500,
                'message'   => "Gagal mengatur denda",
            ]);
        }
    }
}