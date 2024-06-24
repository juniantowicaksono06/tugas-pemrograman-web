<?php
namespace Admin;
use Controllers\Controller;
use Models\BookAuthor;
use Models\MasterAuthor;
use Models\MasterBook;
use Models\MasterPublisher;
use Models\MasterCategory;
use Models\BookCategory;
use Utils\Session;

class BookController extends Controller {
    public function book() {
        $masterBook = new Masterbook();
        $data = $masterBook->getBooks();
        $bookAuthor = new BookAuthor();
        $bookCategory = new BookCategory();
        // $dataAuthor = $bookAuthor->getAuthorByBookId()
        foreach($data as $key => $book) {
            $dataAuthors = $bookAuthor->getAuthorByBookId($book['id']);
            $data[$key]['authors'] = $dataAuthors;
            $dataCategories = $bookCategory->getCategoryByBookId($book['id']);
            $categories = "";
            foreach($dataCategories as $index => $category) {
                $categories .= $category['name'];
                if($index == count($dataCategories) - 1) {
                    break;
                }
                $categories .= ", ";
            }
            $data[$key]['categories'] = $categories;
        }
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
        $masterBook = new MasterBook();
        $book = $masterBook->getBookById($id);
        $bookCategory = $masterBook->getBookCategory($id);
        $newBookCategory = [];
        foreach($bookCategory as $category) {
            array_push($newBookCategory, $category['id_category']);
        }
        $bookAuthor = $masterBook->getBookAuthor($id);
        $newBookAuthor = [];
        foreach($bookAuthor as $author) {
            array_push($newBookAuthor, $author['id_author']);
        }
        if(empty($book)) {
            $sess = new Session();
            $sess->setFlash('warning', 'Buku tidak ditemukan');
            return redirect('/admin/books');
        }
        $authors = new MasterAuthor();
        $dataAuthors = $authors->getAuthors();
        
        $categories = new MasterCategory();
        $dataCategories = $categories->getCategories();
        
        $publishers = new MasterPublisher();
        $dataPublishers = $publishers->getPublishers();
        
        return $this->view("admin/book/edit", [
            "page"  => [
                "parent"    => "Master buku",
                "title"     => "Edit Buku"
            ],
            'dataAuthors'     => $dataAuthors,
            'dataCategories'  => $dataCategories,
            'dataPublishers'  => $dataPublishers,
            'dataEdit'        => [
                'book'          => $book,
                'bookCategory'  => $newBookCategory,
                'bookAuthor'    => $newBookAuthor,
            ]
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'name'              => 'required',
            'id_author'         => 'required|validJson',
            'id_publisher'      => 'required',
            'id_category'       => 'required|validJson',
            'published_year'    => 'required|numeric',
            'description'       => 'optional',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'name'           => "Nama Buku",
            'id_author'      => "Pengarang",
            'id_publisher'   => "Penerbit",
            'id_category'    => "Kategori",
            'published_year' => 'Tahun Terbit',
            'description'    => 'Deskripsi',
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
            $dataToCreate['picture'] = "assets/image/book-picture/default.jpg";
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
        else if($result == 5) {
            if(!empty($picture)) {
                unlink($picture);
            }
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Kategori tidak ditemukan",
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

    public function actionEdit(string $id) {
        $dataValidate = [
            'name'              => 'required',
            'id_author'         => 'required|validJson',
            'id_publisher'      => 'required',
            'id_category'       => 'required|validJson',
            'published_year'    => 'required|numeric',
            'description'       => 'optional',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'name'           => "Nama Buku",
            'id_author'      => "Pengarang",
            'id_publisher'   => "Penerbit",
            'id_category'    => "Kategori",
            'published_year' => 'Tahun Terbit',
            'description'    => 'Deskripsi',
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
        $masterBook = new MasterBook();
        $book = $masterBook->getBookById($id);
        if(empty($book)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Buku tidak ditemukan",
                'error'     => [],
            ]);
        }
        $dataToUpdate = $data;
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
            $dataToUpdate['picture'] = $picture;
            $deleteOld = true;
        }
        else {
            $dataToUpdate['picture'] = $book['picture'];
            $deleteOld = false;
        }
        $result = $masterBook->editBook($id, $dataToUpdate);
        if($result == 1) {
            // Hapus gambar lama
            if($deleteOld && strpos($book['picture'], 'default.png') !== false) {
                unlink($book['picture']);
            }
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil ubah data buku",
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
    
    // DELETE METHOD
    public function actionDeactivate(string $id) {
        $book = new MasterBook();
        $book->deactivateBook($id);
        return jsonResponse(200, [
            'code'      => 200,
            'message'   => "Berhasil menonaktifkan buku",
            'error'     => [],
        ]);
    }
    // POST METHOD
    public function actionReactivate(string $id) {
        $masterBook = new MasterBook();
        $book = $masterBook->getBookById($id);
        if(empty($book)) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => 'Buku tidak ditemukan',
                'error'     => []
            ]);
        }
        if(!empty($book)) {
            if($book['status']  == 1) {
                return jsonResponse(200, [
                    'code'      => 409,
                    'message'   => 'Buku sudah aktif',
                    'error'     => []
                ]);
            }
            $masterBook->reactivateBook($id);
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => 'Buku berhasil di aktivasi',
                'error'     => []
            ]);
        }
    }
}