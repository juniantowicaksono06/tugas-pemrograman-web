<?php

class AuthMiddleware {
    public function handle(Closure $next) {
        $session = new Session();
        if(empty($session->get('user_credential'))) {
            return redirect('/login');
        }
        return $next();
        // call_user_func($callback);
    }
}