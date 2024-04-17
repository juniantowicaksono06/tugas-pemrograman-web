<?php
require_once('./utils/validator.php');
class Controller {
    protected $viewPath;
    protected $views;
    protected $layout;
    protected $layoutPath;
    protected $validator;
    public function __construct($layout = "admin_default") {
        $this->setViewPath('./views');
        $this->setLayoutPath();
        $this->setLayout($layout);
        $this->validator = new Validator();
    }

    protected function setLayoutPath($layout = "layout") {
        $this->layoutPath = $layout;
    }

    protected function setLayout($layout = "admin_default") {
        if(empty($layout) || $layout == "") {
            $this->layout = null;
        }
        else if(!empty($layout)) {
            $this->layout = $this->layoutPath . '/' . $layout . '.php';
        }
        return $this;
    }

    private function setViewPath(String $view) {
        $this->viewPath = $view;
        return $this;
    }

    protected function view(String $view, $data = array()) {
        $viewFile = $this->viewPath . '/' . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            if(!empty($this->layout)) {
                require_once($this->layout);
            }
            else {
                require_once $viewFile;
            }
        } else {
            // Handle view file not found
            echo "View tidak ditemukan";
        }
        return $this;
    }
}