<?php
namespace Middleware;
use Utils\Session;
class UserAuthMiddleware {
    public function handle(\Closure $next) {
        $session = new Session();
        if(empty($session->get('user_credential'))) {
            return redirect('/auth/login');
        }
        return $next();
    }
}