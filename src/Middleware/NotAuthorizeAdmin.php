<?php
namespace Middleware;
use Utils\Session;
class NotAuthorizeAdmin {
    public function handle(\Closure $next) {
        $session = new Session();
        if(!empty($session->get('admin_credential'))) {
            return redirect('/admin');
        }
        return $next();
    }
}