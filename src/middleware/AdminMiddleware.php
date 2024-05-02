<?php

class AdminMiddleware {
    public function handle(Closure $next) {
        $session = new Session();
        $data = $session->get('user_credential');
        if(empty($data)) {
            return redirect('/login');
        }
        else if($data['user_type'] != 1) {
            return redirect('/');
        }
        require_once './models/MasterMenu.php';
        require_once './models/MasterSubMenu.php';
        $masterMenu = new MasterMenu();
        $masterSubMenu = new MasterSubMenu();
        $GLOBALS['menus'] = $masterMenu->getAdminParentMenu();
        $GLOBALS['subMenus'] = $masterSubMenu->getAdminSubMenu(); 
        return $next();
    }
}