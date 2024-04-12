<?php

class Controller {
    protected $viewPath;
    protected $views;
    public function __construct() {
        $this->setView('./views/');
    }

    protected function setView(String $view) {
        $this->viewPath = $view;
        return $this;
    }

    protected function view(String $view, $data = array()) {
        $viewFile = $this->viewPath . '/' . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            // Handle view file not found
            echo "View tidak ditemukan";
        }
    }
}