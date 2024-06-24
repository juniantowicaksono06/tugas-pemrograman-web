<?php
namespace Middleware;
use Models\MasterMenuUser;
use Utils\Session;
class UserMiddleware {
    public function handle(\Closure $next) {
        $session = new Session();
        $data = $session->get('user_credential');
        if(empty($data)) {
            $currentPathExp = explode('/', $_SERVER['REQUEST_URI']);
            $currentPath = $currentPathExp[1]; 
            if($currentPath !== 'explore' && $currentPath !== "") {
                return redirect('/auth/login');
            }
        }
        
        $masterMenu = new MasterMenuUser();
        $GLOBALS['menus'] = $masterMenu->getMenu();
        $GLOBALS['subMenus'] = $masterMenu->getSubMenu();
        return $next();
    }
}