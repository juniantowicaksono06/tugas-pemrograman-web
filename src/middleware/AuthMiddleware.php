<?php

class AuthMiddleware {
    public function handle($callback, $id = null) {
        $session = new Session();
        if(empty($session->get('user_credential'))) {
            return redirect('/login');
        }
        call_user_func($callback, $id);
    }
}