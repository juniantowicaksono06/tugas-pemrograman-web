<?php
require_once './models/MasterProvince.php';
require_once './models/MasterCity.php';


class CityController extends Controller {
    public function city() {
        $authors = new MasterCity();
        $data = $authors->getCities();
        return $this->view("admin/city/list", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Data Kota"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        $province = new MasterProvince();
        $data = $province->getProvinces();
        return $this->view("admin/city/create", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Tambah Kota"
            ],
            'dataProvince'  => $data
        ]);
    }    

    public function edit(string $id) {
        $city = new MasterCity();
        $dataCity = $city->getCityById($id);
        $province = new MasterProvince();
        $dataProvince = $province->getProvinces();
        return $this->view("admin/city/edit", [
            "page"  => [
                "parent"    => "Master data",
                "title"     => "Edit Kota"
            ],
            "dataCity"      => $dataCity,
            "dataProvince"  => $dataProvince,
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'name'           => 'required',
            'id_province'    => 'required'
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'name'           => "Nama Kota",
            'id_province'    => "Provinsi"
        ));
        
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        
        $cities = new MasterCity();
        $result = $cities->createNewCity($data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil tambah kota",
                'error'     => [],
            ]);
        }
        else if($result == 3) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Provinsi tidak ditemukan",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => "Kota sudah ada",
                'error'     => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $dataValidate = [
            'name'           => 'required',
            'id_province'    => 'required'
        ];
        $this->validator->setInputName(array(
            'name'           => "Nama Kota",
            'id_province'    => 'Provinsi'
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
        $publishers = new MasterCity();
        $result = $publishers->editCity($id, $data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil mengubah kota",
                'error'     => [],
            ]);
        }
        else if($result == 3) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Provinsi tidak ditemukan",
                'error'     => [],
            ]);
        }
        else if($result == 4) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Nama Kota sudah ada",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Kota tidak ditemukan",
                'error'     => [],
            ]);
        }
    }
    
    // DELETE METHOD
    public function actionDeactivate(string $id) {
        $user = new MasterCity();
        $user->deactivateCity($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan Kota",
            'error'     => [],
        ]);
    }
    // POST METHOD
    public function actionReactivate(string $id) {
        $masterCity = new MasterCity();
        $city = $masterCity->getCityById($id);
        if(empty($city)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Kota tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($city)) {
            if($city['status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Kota sudah aktif',
                    'error'     => []
                ]);
            }
            $masterCity->reactivateCity($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Kota berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}
