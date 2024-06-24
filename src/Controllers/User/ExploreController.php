<?php
namespace User;
use Controllers\Controller;
use Models\MasterBook;
class ExploreController extends Controller {
    public function explore() {
        $masterBook = new MasterBook();
        $books = $masterBook->getActiveBooks();
        $this->setLayout('user_layout');
        return $this->view("user/explore/list", [
            'page'      => [
                'title'     => "Jelajahi"
            ],
            'data'      => $books
        ]);
    }

    public function detail(string $id) {
        $masterBook = new MasterBook();
        $book = $masterBook->getBookById($id, true, true);
        $bookAuthors = $masterBook->getBookAuthor($id);
        $this->setLayout('user_layout');
        return $this->view("user/explore/detail", [
            'page'      => [
                'title'     => "Jelajahi"
            ],
            'dataBook'      => $book,
            'dataAuthors'   => $bookAuthors,
        ]);
    }
}
