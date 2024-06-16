<?php
require_once './models/MasterAuthor.php';

class AuthorController extends Controller {
    public function authors() {
        $authors = new MasterAuthor();
        $data = $authors->getAuthors();
        return $this->view("admin/author/list", [
            "page"  => [
                "parent"    => "Buku",
                "title"     => "Data Pengarang"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        return $this->view("admin/author/create", [
            "page"  => [
                "parent"    => "Buku",
                "title"     => "Tambah Pengarang"
            ]
        ]);
    }    

    public function edit(string $id) {
        $user = new MasterAuthor();
        $data = $user->getAuthorByID($id);
        return $this->view("admin/author/edit", [
            "page"  => [
                "parent"    => "Buku",
                "title"     => "Edit Pengarang"
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
            'name'           => "Nama Pengarang",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $authors = new MasterAuthor();
        $result = $authors->createNewAuthor($data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil tambah Pengarang",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => "Pengarang sudah ada",
                'error'     => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $dataValidate = [
            'name'           => 'required',
        ];
        $this->validator->setInputName(array(
            'name'           => "Nama Pengarang",
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
        $publishers = new MasterAuthor();
        $result = $publishers->editAuthor($id, $data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil mengubah Pengarang",
                'error'     => [],
            ]);
        }
        else if($result == 3) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Nama Pengarang sudah ada",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Pengarang tidak ditemukan",
                'error'     => [],
            ]);
        }
    }
    
    // DELETE METHOD
    public function actionDelete(string $id) {
        $user = new MasterAuthor();
        $user->deleteAuthor($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan Pengarang",
            'error'     => [],
        ]);
    }
    // POST METHOD
    public function actionActivate(string $id) {
        $publishers = new MasterAuthor();
        $publisher = $publishers->getAuthorByID($id);
        if(empty($publisher)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Pengarang tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($publisher)) {
            if($publisher['status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Pengarang sudah aktif',
                    'error'     => []
                ]);
            }
            $publishers->activateAuthor($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Pengarang berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}
