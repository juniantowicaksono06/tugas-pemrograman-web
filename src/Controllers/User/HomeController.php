<?php
    namespace User;
    use Controllers\Controller;
    use Models\MasterProvince;
    use Models\MasterCity;
    use Models\MasterMember;
    use Utils\Session;
    use Service\EmailService;
    class HomeController extends Controller {
    
        public function home() {
            $this->setLayout('user_layout');
            return $this->view("user/home/index", [
                'page'  => [
                    'title'     => "Beranda"
                ]
            ]);
        }
    
    }