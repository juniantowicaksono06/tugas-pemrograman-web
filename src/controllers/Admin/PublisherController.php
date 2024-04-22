<?php
require_once './models/MasterPublisher.php';

class PublisherController extends Controller {
    public function publishers() {
        $publishers = new MasterPublisher();
        $data = $publishers->getPublishers();
        return $this->view("admin/publishers/list", [
            "page"  => [
                "parent"    => "Buku",
                "title"     => "Manajemen Penerbit"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        return $this->view("admin/publishers/create", [
            "page"  => [
                "parent"    => "Buku",
                "title"     => "Tambah Penerbit"
            ]
        ]);
    }    

    public function edit(string $id) {
        $user = new MasterPublisher();
        $data = $user->getPublisherByID($id);
        return $this->view("admin/publishers/edit", [
            "page"  => [
                "parent"    => "Buku",
                "title"     => "Edit Penerbit"
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
            'name'           => "Nama Penerbit",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $publishers = new MasterPublisher();
        $result = $publishers->createNewPublisher($data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil tambah penerbit",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => "Penerbit sudah ada",
                'error'     => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $dataValidate = [
            'name'           => 'required',
        ];
        $this->validator->setInputName(array(
            'name'           => "Nama Penerbit",
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
        $publishers = new MasterPublisher();
        $result = $publishers->editPublisher($id, $data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil mengubah penerbit",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Penerbit tidak ditemukan",
                'error'     => [],
            ]);
        }
    }
    
    // DELETE METHOD
    public function actionDelete(string $id) {
        $user = new MasterPublisher();
        $user->deletePublisher($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan penerbit",
            'error'     => [],
        ]);
    }
    // POST METHOD
    public function actionActivate(string $id) {
        $publishers = new MasterPublisher();
        $publisher = $publishers->getPublisherByID($id);
        if(empty($publisher)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Penerbit tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($publisher)) {
            if($publisher['status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Penerbit sudah aktif',
                    'error'     => []
                ]);
            }
            $publishers->activatePublisher($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Penerbit berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}
