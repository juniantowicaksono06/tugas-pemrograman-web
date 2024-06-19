<?php
namespace Middleware;
use Models\MasterMenu;
use Utils\Session;
class AdminMiddleware {
    public function handle(\Closure $next) {
        $session = new Session();
        $data = $session->get('admin_credential');
        if(empty($data)) {
            return redirect('/admin/auth/login');
        }
        
        $masterMenu = new MasterMenu();
        $GLOBALS['menus'] = $masterMenu->getAdminMenu();
        $GLOBALS['subMenus'] = $masterMenu->getAdminSubMenu();
        return $next();
    }
}