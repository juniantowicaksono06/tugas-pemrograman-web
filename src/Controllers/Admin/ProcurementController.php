<?php
namespace Admin;
use Controllers\Controller;
use Models\Procurement;
use Utils\Session;
use Models\MasterBook;
use Models\BookAuthor;
use Models\BookCategory;

class ProcurementController extends Controller {
    public function procurement() {
        $procurements = new Procurement();
        $data = $procurements->getProcurements();
        return $this->view("admin/procurement/list", [
            "page"  => [
                "parent"    => "Sirkulasi",
                "title"     => "Data Pengadaan"
            ],
            "data"  => $data
        ]);
    }
    
    public function create() {
        $sess = new Session();
        $data = $sess->get('detailDataPengadaan');
        $data = empty($data) ? [] : $data;
        return $this->view("admin/procurement/create", [
            "page"  => [
                "parent"    => "Sirkulasi",
                "title"     => "Buat Pengadaan"
            ],
            'data'     => $data
        ]);
    }   
    
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
        return $this->view("admin/procurement/book", [
            "page"  => [
                "parent"    => "Sirkulasi",
                "title"     => "Pilih Buku"
            ],
            "data"  => $data
        ]);
    }

    public function actionSelectBook(string $id) {
        $dataValidate = [
            'book'           => 'required|validJson',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'book'           => "Buku",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $session = new Session();
        $detailData = $session->get('detailDataPengadaan');
        $detailData = empty($detailData) ? [] : $detailData;
        $booksId = $session->get('dataPengadaanBooksId');
        $booksId = empty($booksId) ? [] : $booksId;
        array_push($booksId, $id);
        array_push($detailData, json_decode($data['book'], true));
        $session->set('detailDataPengadaan', $detailData);
        $session->set('dataPengadaanBooksId', $booksId);
        return jsonResponse(200, [
            'code'      => 200,
        ]);
    }

    public function actionDeselectBook(string $id) {
        $session = new Session();
        $detailData = $session->get('detailDataPengadaan');
        $detailData = empty($detailData) ? [] : $detailData;
        $booksId = $session->get('dataPengadaanBooksId');
        $booksId = empty($booksId) ? [] : $booksId;
        foreach($booksId as $index => $bookId) {
            if($bookId == $id) {
                unset($booksId[$index]);
                unset($detailData[$index]);
                break;
            }
        }
        $booksId = array_values($booksId);
        $detailData = array_values($detailData);
        $session->set('detailDataPengadaan', $detailData);
        $session->set('dataPengadaanBooksId', $booksId);
        return jsonResponse(200, [
            'code'      => 200,
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'data'           => 'required|validJson',
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'valid'           => "Data",
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $authors = new Procurement();
        $d = json_decode($data['data'], true);

        $result = $authors->createProcurement($d);
        if($result == 1) {
            $session = new Session();
            $session->remove('detailDataPengadaan');
            $session->remove('dataPengadaanBooksId');
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil melakukan pengadaan",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 500,
                'message'   => "Gagal melakukan pengadaan",
                'error'     => [],
            ]);
        }
    }
}
