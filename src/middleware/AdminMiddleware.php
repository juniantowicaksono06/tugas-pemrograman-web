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
        return $next();
        // call_user_func($callback);
    }
}