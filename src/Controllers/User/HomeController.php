<?php
    namespace User;
    use Controllers\Controller;
    use Models\MasterProvince;
    use Models\MasterCity;
    use Models\MasterMember;
    use Utils\Session;
    use Service\EmailService;
    use Models\BorrowingBook;
    class HomeController extends Controller {
    
        public function home() {
            $borrowing = new BorrowingBook();
            if(!empty($_SESSION['user_credential'])) {
                $totalBorrow = $borrowing->getTotalBorrowing($_SESSION['user_credential']['id']);
                $totalFines = $borrowing->getTotalFines($_SESSION['user_credential']['id']);
                $this->setLayout('user_layout');
                return $this->view("user/home/index", [
                    'page'  => [
                        'title'     => "Beranda"
                    ],
                    'totalBorrow'      => $totalBorrow['total'],
                    'totalFines'       => $totalFines['total'],
                ]);
            }
            $this->setLayout(null);
            return $this->view('user/home/landingpage');
        }
    
    }