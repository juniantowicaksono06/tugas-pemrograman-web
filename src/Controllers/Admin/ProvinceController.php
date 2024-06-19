<?php
namespace Admin;
use Controllers\Controller;
use Models\MasterProvince;

class ProvinceController extends Controller {
    public function province() {
        $authors = new MasterProvince();
        $data = $authors->getProvinces();
        return $this->view("admin/province/list", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Data Provinsi"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        return $this->view("admin/province/create", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Tambah Provinsi"
            ]
        ]);
    }    

    public function edit(string $id) {
        $user = new MasterProvince();
        $data = $user->getProvinceById($id);
        return $this->view("admin/province/edit", [
            "page"  => [
                "parent"    => "Master data",
                "title"     => "Edit Provinsi"
            ],
            "data"  => $data
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'name'           => 'required',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'name'           => "Nama Provinsi",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $provinces = new MasterProvince();
        $result = $provinces->createNewProvince($data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil tambah provinsi",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => "Provinsi sudah ada",
                'error'     => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $dataValidate = [
            'name'           => 'required',
        ];
        $this->validator->setInputName(array(
            'name'           => "Nama Provinsi",
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
        $publishers = new MasterProvince();
        $result = $publishers->editProvince($id, $data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil mengubah provinsi",
                'error'     => [],
            ]);
        }
        else if($result == 3) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Nama Provinsi sudah ada",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Provinsi tidak ditemukan",
                'error'     => [],
            ]);
        }
    }
    
    // DELETE METHOD
    public function actionDeactivate(string $id) {
        $user = new MasterProvince();
        $user->deactivateProvince($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan Provinsi",
            'error'     => [],
        ]);
    }
    // POST METHOD
    public function actionReactivate(string $id) {
        $publishers = new MasterProvince();
        $publisher = $publishers->getProvinceById($id);
        if(empty($publisher)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Provinsi tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($publisher)) {
            if($publisher['status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Provinsi sudah aktif',
                    'error'     => []
                ]);
            }
            $publishers->reactivateProvince($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Provinsi berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}
