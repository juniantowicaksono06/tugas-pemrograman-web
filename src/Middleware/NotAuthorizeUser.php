<?php
namespace Middleware;
use Utils\Session;
class NotAuthorizeUser {
    public function handle(\Closure $next) {
        $session = new Session();
        if(!empty($session->get('user_credential'))) {
            return redirect('/');
        }
        return $next();
    }
}