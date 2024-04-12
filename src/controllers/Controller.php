<?php
require_once('./utils/validator.php');
class Controller {
    protected $viewPath;
    protected $views;
    protected $layout;
    protected $validator;
    public function __construct($layout = "default") {
        $this->setView('./views/');
        $this->setLayout($layout);
        $this->validator = new Validator();
    }

    protected function setLayout($layout = "default") {
        if(empty($layout) || $layout == "") {
            $this->layout = null;
        }
        else if(!empty($layout)) {
            $this->layout = $layout;
        }
    }

    protected function setView(String $view) {
        $this->viewPath = $view;
        return $this;
    }

    protected function view(String $view, $data = array()) {
        $viewFile = $this->viewPath . '/' . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            if(!empty($this->layout)) {
            }
            else {
                require_once $viewFile;
            }
        } else {
            // Handle view file not found
            echo "View tidak ditemukan";
        }
    }
}