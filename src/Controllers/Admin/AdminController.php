<?php

namespace Admin;
use Controllers\Controller;
use Models\MasterBook;
use Models\MasterMember;

class AdminController extends Controller {
    public function home() {
        $masterBook = new MasterBook();
        $totalBook = $masterBook->getTotalBook();
        $masterMember = new MasterMember();
        $totalMember = $masterMember->getTotalMember();
        return $this->view("admin/home", [
            "page" => [
                'title' => 'Dashboard'
            ],
            'totalBook'     => $totalBook['total_book'],
            'totalMember'   => $totalMember['total_member']
        ]);
    }
}