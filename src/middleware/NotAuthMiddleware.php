<?php

class NotAuthMiddleware {
    public function handle($callback, $id = null) {
        $session = new Session();
        if(!empty($session->get('user_credential'))) {
            return redirect('/admin');
        }
        call_user_func($callback, $id);
    }
}