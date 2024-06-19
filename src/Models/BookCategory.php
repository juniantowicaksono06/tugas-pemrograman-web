<?php
    namespace Models;
    use Models\Model;
    class BookCategory extends Model {
        private $tableName = "book_category";
        public function getCategoryByBookId(string $id) {
            $sql = "SELECT ba.id_category, mc.name FROM ". $this->tableName ." ba LEFT JOIN master_category mc ON mc.id = ba.id_category WHERE id_book = :id_book";
            $books = $this->connection->fetchAll($sql, [':id_book'  => $id]);
            return $books;
        }
    }