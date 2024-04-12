<?php

class AuthController extends Controller {
    public function register() {
        return $this->view("register");
    }

    
    public function login() {
        return $this->view("login");
    }
}