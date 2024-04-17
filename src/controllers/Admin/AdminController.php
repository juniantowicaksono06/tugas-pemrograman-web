<?php

class AdminController extends Controller {
    public function home() {
        return $this->view("admin/home", [
            "page" => [
                'title' => 'Dashboard'
            ]
        ]);
    }
}