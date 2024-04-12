<?php

class Session {
    public function __construct() {
        session_start();
    }

    public function set(string $key, mixed $value) {
        $_SESSION[$key] = $value;
    }

    public function get(string $key) { 
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function setFlash(string $key, mixed $value) {
        $_SESSION['flash_'.$key] = $value;
    }
    public function getFlash(string $key) {
        $value = isset($_SESSION['flash_' . $key]) ? $_SESSION['flash_' . $key] : null;
        unset($_SESSION['flash_' . $key]);
        return $value;
    }
    public function destroy() {
        session_destroy();
    }
}