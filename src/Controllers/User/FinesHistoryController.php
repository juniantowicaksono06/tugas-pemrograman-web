<?php
namespace User;
use Controllers\Controller;
use Models\BorrowingBook;

class FinesHistoryController extends Controller {
    
    public function history() {
        // Jangan set layout pada login
        $this->setLayout('user_layout');
        $procurement = new BorrowingBook();
        $data = $procurement->getBorrowingsFines();
        return $this->view("user/fines/history", [
            'page'      => [
                'title'     => "Riwayat Denda"
            ],
            'data'      => $data
        ]);
    }

}