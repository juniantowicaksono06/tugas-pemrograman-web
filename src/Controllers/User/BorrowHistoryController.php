<?php
namespace User;
use Controllers\Controller;
use Models\BorrowingBook;
use Models\Procurement;
use Models\BorrowingDetail;
use Models\SettingFines;

class BorrowHistoryController extends Controller {
    
    public function history() {
        $this->setLayout('user_layout');
        $borrowingBook = new BorrowingBook();
        $data = $borrowingBook->getBorrowings();
        $masterFines = new SettingFines();
        $fines = $masterFines->getFines();
        return $this->view("user/borrow/history", [
            'page'      => [
                'title'     => "Riwayat Pinjam"
            ],
            'data'      => $data,
            'fines'     => $fines
        ]);
    }

    public function detail(string $id) {
        $this->setLayout('user_layout');
        $borrowingBook = new BorrowingBook();
        $borrowingDetail = new BorrowingDetail();
        $data = $borrowingBook->getBorrowingById($id);
        $detailBook = $borrowingDetail->getBooksById($id);
        return $this->view("user/borrow/detail", [
            'page'      => [
                'title'     => "Detail Pinjam"
            ],
            'data'      => $data,
            'detail'    => $detailBook
        ]);
    }

}