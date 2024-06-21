<?php
    namespace Models;
    class BorrowingDetail extends Model {
        private $tableName = "borrowing_detail";
        public function getAuthorByBookId(string $id) {
            $sql = "SELECT ba.id_author, ma.name FROM ". $this->tableName ." ba LEFT JOIN master_author ma ON ma.id = ba.id_author WHERE id_book = :id_book";
            $books = $this->connection->fetchAll($sql, [':id_book'  => $id]);
            return $books;
        }
    }