<?php
namespace Middleware;
// use Models\MasterMenu;
use Utils\Session;
class UserMiddleware {
    public function handle(\Closure $next) {
        $session = new Session();
        $data = $session->get('user_credential');
        if(empty($data)) {
            return redirect('/auth/login');
        }
        
        // $masterMenu = new MasterMenu();
        // $GLOBALS['menus'] = $masterMenu->getAdminMenu();
        // $GLOBALS['subMenus'] = $masterMenu->getAdminSubMenu();
        return $next();
    }
}