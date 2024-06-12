<?php

class AdminMiddleware {
    public function handle(Closure $next) {
        $session = new Session();
        $data = $session->get('admin_credential');
        if(empty($data)) {
            return redirect('/admin/auth/login');
        }
        
        require_once './models/MasterMenu.php';
        // require_once './models/MasterSubMenu.php';
        $masterMenu = new MasterMenu();
        // $masterSubMenu = new MasterSubMenu();
        $GLOBALS['menus'] = $masterMenu->getAdminMenu();
        $GLOBALS['subMenus'] = $masterMenu->getAdminSubMenu();
        return $next();
    }
}