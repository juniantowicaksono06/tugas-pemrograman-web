<?php

class AuthController extends Controller {
    public function register() {
        // Jangan set layout pada register
        $this->setLayout(null);
        return $this->view("auth/register");
    }
    
    public function login() {
        // Jangan set layout pada login
        $this->setLayout(null);
        return $this->view("auth/login");
    }

    public function actionLogin() {

    }
}