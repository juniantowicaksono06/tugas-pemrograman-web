<?php

require_once './models/MasterBook.php';

class AdminController extends Controller {
    public function home() {
        $masterBook = new MasterBook();
        $totalBook = $masterBook->getTotalBook();
        return $this->view("admin/home", [
            "page" => [
                'title' => 'Dashboard'
            ],
            'totalBook' => $totalBook['total_book']
        ]);
    }
}