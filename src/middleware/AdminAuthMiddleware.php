<?php

class AdminAuthMiddleware {
    public function handle(Closure $next) {
        $session = new Session();
        if(empty($session->get('user_credential'))) {
            return redirect('/admin/auth/login');
        }
        return $next();
    }
}