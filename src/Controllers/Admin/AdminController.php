<?php

namespace Admin;
use Controllers\Controller;
use Models\MasterBook;

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