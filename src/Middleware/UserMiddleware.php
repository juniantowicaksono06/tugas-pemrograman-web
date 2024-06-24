<?php
namespace Middleware;
use Models\MasterMenuUser;
use Utils\Session;
class UserMiddleware {
    public function handle(\Closure $next) {
        $session = new Session();
        $data = $session->get('user_credential');
        if(empty($data)) {
            return redirect('/auth/login');
        }
        
        $masterMenu = new MasterMenuUser();
        $GLOBALS['menus'] = $masterMenu->getMenu();
        $GLOBALS['subMenus'] = $masterMenu->getSubMenu();
        return $next();
    }
}