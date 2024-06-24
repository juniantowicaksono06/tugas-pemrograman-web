<?php
namespace Admin;
use Controllers\Controller;
use Models\Procurement;
use Utils\Session;
use Models\MasterBook;
use Models\BookAuthor;
use Models\BookCategory;
use Models\MasterMember;
use Models\BorrowingBook;
use Models\SettingFines;
use Models\BorrowingDetail;

class BorrowingBookController extends Controller {
    public function borrowing() {
        $borrowing = new BorrowingBook();
        $data = $borrowing->getBorrowings();
        $settingFines = new SettingFines();
        $fines = $settingFines->getFines();
        return $this->view("admin/borrowing-book/list", [
            "page"  => [
                "parent"    => "Sirkulasi",
                "title"     => "Data Peminjaman Buku"
            ],
            "data"  => $data,
            'fines' => $fines,
        ]);
    }
    
    public function create() {
        $masterMember = new MasterMember();
        $allActiveMembers = $masterMember->getActiveMembers();
        $sess = new Session();
        $data = $sess->get('detailDataPeminjaman');
        $data = empty($data) ? [] : $data;
        return $this->view("admin/borrowing-book/create", [
            "page"  => [
                "parent"    => "Sirkulasi",
                "title"     => "Buat Peminjaman"
            ],
            'data'              => $data,
            'allActiveMembers'  => $allActiveMembers
        ]);
    }   
    
    public function book() {
        $masterBook = new Masterbook();
        $data = $masterBook->getStockBooks();
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
        return $this->view("admin/borrowing-book/book", [
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
        $detailData = $session->get('detailDataPeminjaman');
        $detailData = empty($detailData) ? [] : $detailData;
        $booksId = $session->get('dataPeminjamanBooksId');
        $booksId = empty($booksId) ? [] : $booksId;
        array_push($booksId, $id);
        array_push($detailData, json_decode($data['book'], true));
        $session->set('detailDataPeminjaman', $detailData);
        $session->set('dataPeminjamanBooksId', $booksId);
        return jsonResponse(200, [
            'code'      => 200,
        ]);
    }

    public function detail(string $id) {
        $borrowingBook = new BorrowingBook();
        $borrowingDetail = new BorrowingDetail();
        $data = $borrowingBook->getBorrowingById($id);
        $detailBook = $borrowingDetail->getBooksById($id);
        return $this->view("admin/borrowing-book/detail", [
            'page'      => [
                'title'     => "Detail Pinjam"
            ],
            'data'      => $data,
            'detail'    => $detailBook
        ]);
    }

    public function actionDeselectBook(string $id) {
        $session = new Session();
        $detailData = $session->get('detailDataPeminjaman');
        $detailData = empty($detailData) ? [] : $detailData;
        $booksId = $session->get('dataPeminjamanBooksId');
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
        $session->set('detailDataPeminjaman', $detailData);
        $session->set('dataPeminjamanBooksId', $booksId);
        return jsonResponse(200, [
            'code'      => 200,
        ]);
    }

    public function actionCreate() {
        $dataValidate = [
            'data'           => 'required|validJson',
            'borrowing_date' => 'required',
            'borrower'       => 'required'
        ];
        $data = $_POST;
        $this->validator->setInputName(array(
            'data'           => "Data Buku",
            'borrower'       => 'Nama Peminjam',
            'borrowing_date' => 'Tanggal Pinjam'
        ));
        $inputValid = $this->validator->validate($dataValidate, $data);
        if(!$inputValid) {
            return jsonResponse(200, [
                'code'      => 400,
                'message'   => "Bad Request",
                'error'     => $this->validator->getMessages()
            ]);
        }
        $borrowingBook = new BorrowingBook();
        $d = json_decode($data['data'], true);

        $result = $borrowingBook->createBorrow($d, $data['borrowing_date'], $data['borrower']);
        if($result === 1) {
            $session = new Session();
            $session->remove('detailDataPeminjaman');
            $session->remove('dataPeminjamanBooksId');
            return jsonResponse(200, [
                'code'      => 201,
                'message'   => "Berhasil melakukan pengadaan",
                'error'     => [],
            ]);
        }
        else if($result === 3) {
            return jsonResponse(200, [
                'code'      => 403,
                'message'   => "Ada buku yang stoknya tidak mencukupi",
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

    public function actionReturn(string $id) {
        $borrowingBook = new BorrowingBook();
        $result = $borrowingBook->returnBook($id);
        if($result === 1) {
            return jsonResponse(200, [
                'code'      => 200,
                'message'   => "Buku berhasil dikembalikan",
                'error'     => [],
            ]);
        }
        else if($result === 2) {
            return jsonResponse(200, [
                'code'      => 404,
                'message'   => "Peminjaman tidak ditemukan",
                'error'     => [],
            ]);
        }
        else if($result === 3) {
            return jsonResponse(200, [
                'code'      => 403,
                'message'   => "Peminjaman ini sudah dikembalikan",
                'error'     => [],
            ]);
        }
        else {
            return jsonResponse(200, [
                'code'      => 500,
                'message'   => "Peminjaman gagal diproses",
                'error'     => [],
            ]);
        }
    }
}
