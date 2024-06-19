<?php
namespace Admin;
use Controllers\Controller;
use Models\MasterCategory;

class CategoryController extends Controller {
    
    public function category() {
        $category = new MasterCategory();
        $data = $category->getCategories();
        return $this->view("admin/category/list", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Data Kategori"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        return $this->view("admin/category/create", [
            "page"  => [
                "parent"    => "Master Data",
                "title"     => "Tambah Kategori"
            ]
        ]);
    }    

    public function edit(string $id) {
        $category = new MasterCategory();
        $data = $category->getCategoryById($id);
        return $this->view("admin/category/edit", [
            "page"  => [
                "parent"    => "Master data",
                "title"     => "Edit Kategori"
            ],
            "data"  => $data
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'name'           => 'required'
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'name'           => "Nama Kategori",
        ));
        
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        
        $cities = new MasterCategory();
        $result = $cities->createNewCategory($data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil tambah kateogri",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 409,
                'message'   => "Kateogri sudah ada",
                'error'     => [],
            ]);
        }
    }

    public function actionEdit($id) {
        $dataValidate = [
            'name'           => 'required',
        ];
        $this->validator->setInputName(array(
            'name'           => "Nama Kategori"
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
        $publishers = new MasterCategory();
        $result = $publishers->editCategory($id, $data);
        if($result == 1) {
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil mengubah kategori",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Kategori tidak ditemukan",
                'error'     => [],
            ]);
        }
    }
    
    // DELETE METHOD
    public function actionDeactivate(string $id) {
        $category = new MasterCategory();
        $category->deactivateCategory($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan Kategori",
            'error'     => [],
        ]);
    }
    // POST METHOD
    public function actionReactivate(string $id) {
        $masterCategory = new MasterCategory();
        $category = $masterCategory->getCategoryById($id);
        if(empty($category)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Kategori tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($category)) {
            if($category['status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Kategori sudah aktif',
                    'error'     => []
                ]);
            }
            $masterCategory->reactivateCategory($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Kategori berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}