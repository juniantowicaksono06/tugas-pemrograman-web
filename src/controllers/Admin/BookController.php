<?php
require_once './models/MasterAuthor.php';
require_once './models/MasterBook.php';
require_once './models/MasterPublisher.php';
require_once './models/MasterCategory.php';

class BookController extends Controller {
    public function book() {
        $book = new Masterbook();
        $data = $book->getBooks();
        return $this->view("admin/book/list", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Data Buku"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        $authors = new MasterAuthor();
        $dataAuthors = $authors->getAuthors();
        
        $categories = new MasterCategory();
        $dataCategories = $categories->getCategories();
        
        $publishers = new MasterPublisher();
        $dataPublishers = $publishers->getPublishers();
        return $this->view("admin/book/create", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Tambah Kota"
            ],
            'dataAuthors'     => $dataAuthors,
            'dataCategories'  => $dataCategories,
            'dataPublishers'  => $dataPublishers,
        ]);
    }    

    public function edit(string $id) {
        $city = new MasterCity();
        $dataCity = $city->getCityById($id);
        $province = new MasterProvince();
        $dataProvince = $province->getProvinces();
        return $this->view("admin/book/edit", [
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
            'name'              => 'required',
            'id_author'         => 'required',
            'id_publisher'      => 'required',
            'id_category'       => 'required|validJson',
            'publish_date'      => 'required|validDate',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'name'           => "Nama Kota",
            'id_author'      => "Pengarang",
            'id_publisher'   => "Penerbit",
            'id_category'    => "Kategori",
            'publish_date'   => 'Tanggal Terbit',
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
        $book = new MasterBook();
        $dataToCreate = $data;
        if(!empty($_FILES['picture'])) {
            $uploadImage = imageUpload($_FILES['picture'], "assets/image/book-picture/");
            if($uploadImage['status'] != 1) {
                return jsonResponse(200, [
                    'code'      => 400,
                    'message'   => "Upload image gagal",
                    'error'     => $uploadImage['message'], 
                ]);
            }
            
            $picture = $uploadImage['uploadedFile'];
            $dataToCreate['picture'] = $picture;
        }
        else {
            $dataToCreate['picture'] = "/assets/image/book-picture/default.jpg";
        }
        $result = $book->createNewBook($dataToCreate);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil tambah buku",
                'error'     => [],
            ]);
        }
        else if($result == 3) {
            if(!empty($picture)) {
                unlink($picture);
            }
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Pengarang tidak ditemukan",
                'error'     => [],
            ]);
        }
        else if($result == 4) {
            if(!empty($picture)) {
                unlink($picture);
            }
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Penerbit tidak ditemukan",
                'error'     => [],
            ]);
        }
        else {
            if(!empty($picture)) {
                unlink($picture);
            }
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => "Buku sudah ada",
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