<?php
    namespace Models;
    class ProcurementDetail extends Model {
        private $tableName = "procurement_detail";
        
        private $stockTable = "book_stock";
        private $bookTable = "master_book";
        private $authorTable = "master_author";
        private $bookAuthorTable = "book_author";
        public function getAuthorByBookId(string $id) {
            $sql = "SELECT ba.id_author, ma.name FROM borrowing_detail bd
            LEFT JOIN ".$this->stockTable." bs ON bs.id = bd.id_stock 
            LEFT JOIN ".$this->bookAuthorTable." ba ON ba.id_book = bs.book_id
            LEFT JOIN ". $this->authorTable ." ma ON ma.id = ba.id_author 
            WHERE ba.id_book = :id_book
            GROUP BY id_author, ba.id_book";
            $books = $this->connection->fetchAll($sql, [':id_book'  => $id]);
            return $books;
        }

        public function getBooksById(string $id) {
            $sql = "SELECT * FROM " . $this->tableName . " bd LEFT JOIN ".$this->stockTable." bs ON bs.id = bd.id_stock WHERE procurement_id = :procurement_id";
            $borrrowings = $this->connection->fetchAll($sql, [
                ':procurement_id'      => $id
            ]);
            // var_dump(count($borrrowings));exit;
            $book = new MasterBook();
            foreach($borrrowings as $index => $borrow) {
                $authors = $this->getAuthorByBookId($borrow['book_id']);
                $borrrowings[$index]['authors'] = $authors;
                $currentBook = $book->getBookById($borrow['book_id'], false, true);
                $borrrowings[$index]['publisher'] = $currentBook['name'];
                $borrrowings[$index]['book_title'] = $currentBook['title'];
            }
            return $borrrowings;
        }
    }