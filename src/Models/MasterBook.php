<?php
    namespace Models;
    class MasterBook extends Model {
        private $tableName = "master_book";
        private $referencedOnPublisher = 'master_publisher';
        private $referencedOnAuthor = 'master_author';
        private $referencedOnCategory = 'master_category';
        private $referenceBookAuthor = 'book_author';
        private $referenceBookCategory = 'book_category';
        private $bookStockTable = 'book_stock';
        
        public function getBook(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }
        public function getBooks() {
            $books = $this->connection->fetchAll("SELECT b.id AS id, b.title as title, mp.name AS publisher_name, b.status, b.created_at FROM ". $this->tableName ." b LEFT JOIN master_publisher mp ON mp.id = b.id_publisher");
            return $books;
        }

        public function getStockBooks() {
            $stocks =  $this->connection->fetchAll("SELECT SUM(`in`) AS stock, book_id FROM book_stock GROUP BY book_id HAVING stock > 0");
            $books = [];
            foreach ($stocks as $book) {
                if (isset($book["book_id"])) {
                    array_push($books, $this->connection->fetchOne("SELECT b.id AS id, b.title as title, mp.name AS publisher_name, b.status, b.created_at FROM ". $this->tableName ." b LEFT JOIN master_publisher mp ON mp.id = b.id_publisher WHERE b.id = :book_id", [
                        ':book_id'  => $book['book_id']
                    ]));
                }
            }
            return $books;
        }

        public function createNewBook(array $data) {
            try {
                $authorsId = json_decode($data['id_author']);
                $searchAuthorsId = rtrim(str_repeat('?,', count($authorsId)), ',');
                $authors = $this->connection->fetchAll("SELECT * FROM " . $this->referencedOnAuthor . " WHERE id IN ($searchAuthorsId)", $authorsId);
                $publisher = $this->connection->fetchOne("SELECT * FROM " . $this->referencedOnPublisher . " WHERE id = :id_publisher", [
                    ':id_publisher'    => $data['id_publisher']
                ]);
                if(count($authorsId) !== count($authors)) {
                    return 3;
                }
                if(empty($publisher)) {
                    return 4;
                }
                $bookId = UUIDv4();
                $categoriesId = json_decode($data['id_category']);
                $searchCategoriesId = rtrim(str_repeat('?,', count($categoriesId)), ',');
                $categories = $this->connection->fetchAll("SELECT * FROM " . $this->referencedOnCategory . " WHERE id IN ($searchCategoriesId)", $categoriesId);
                if(count($categoriesId) !== count($categories)) {
                    return 5;
                }
                $this->connection->commands("INSERT INTO ". $this->tableName ." (id, title, barcode, id_publisher, published_year, picture, status) 
                VALUES(:id, :title, :barcode, :id_publisher, :published_year, :picture, :status)", [
                    ':id'               => $bookId,
                    ':title'            => $data['name'],
                    ':id_publisher'     => $data['id_publisher'],
                    ':published_year'   => $data['published_year'],
                    ':barcode'          => $data['barcode'],
                    ':picture'          => $data['picture'],
                    ':status'           => 1,
                ]);
                $sql = "INSERT INTO ". $this->referenceBookCategory ." (id, id_book, id_category) 
                VALUES(:id, :id_book, :id_category)";
                foreach($categoriesId as $categoryId) {
                    $this->connection->commands($sql, [
                        ':id'          => UUIDv4(),
                        ':id_book'     => $bookId,
                        ':id_category' => $categoryId,
                    ]);
                }
                $sql = "INSERT INTO ". $this->referenceBookAuthor ." (id, id_book, id_author) 
                VALUES(:id, :id_book, :id_author)";
                foreach($authorsId as $authorId) {
                    $this->connection->commands($sql, [
                        ':id'          => UUIDv4(),
                        ':id_book'     => $bookId,
                        ':id_author'   => $authorId,
                    ]);
                }
                return 1;
            }
            catch(\Exception $e) {
                return 0;
            }
        }

        public function getTotalBook() {
            $book = $this->connection->fetchOne("SELECT COUNT(id) AS total_book FROM " . $this->tableName . " WHERE status = 1");
            return $book;
        }

        public function editBook(string $bookId, array $data) {
            try {
                $authorsId = json_decode($data['id_author']);
                $searchAuthorsId = rtrim(str_repeat('?,', count($authorsId)), ',');
                $authors = $this->connection->fetchAll("SELECT * FROM " . $this->referencedOnAuthor . " WHERE id IN ($searchAuthorsId)", $authorsId);
                $publisher = $this->connection->fetchOne("SELECT * FROM " . $this->referencedOnPublisher . " WHERE id = :id_publisher", [
                    ':id_publisher'    => $data['id_publisher']
                ]);
                if(count($authorsId) !== count($authors)) {
                    return 3;
                }
                if(empty($publisher)) {
                    return 4;
                }
                $categoriesId = json_decode($data['id_category']);
                $searchCategoriesId = rtrim(str_repeat('?,', count($categoriesId)), ',');
                $categories = $this->connection->fetchAll("SELECT * FROM " . $this->referencedOnCategory . " WHERE id IN ($searchCategoriesId)", $categoriesId);
                if(count($categoriesId) !== count($categories)) {
                    return 5;
                }
                $bookCategories = $this->connection->fetchAll("SELECT id_category FROM " . $this->referenceBookCategory . " WHERE id_book = :id_book", [
                    ':id_book'  => $bookId
                ]);
                $existingCategory = [];
                foreach($bookCategories as $category) {
                    if(!in_array($category['id_category'], $categoriesId)) {
                        $this->connection->commands('DELETE FROM ' . $this->referenceBookCategory . ' WHERE id_category = :id_category', [
                            ':id_category'  => $category['id_category']
                        ]);
                    }
                    else {
                        array_push($existingCategory, $category['id_category']);
                    }
                }
                foreach($categoriesId as $categoryId) {
                    if(!in_array($categoryId, $existingCategory)) {
                        $this->connection->commands("INSERT INTO " . $this->referenceBookCategory . '(id, id_book, id_category) VALUES(:id, :id_book, :id_category)', [
                            ':id'             => UUIDv4(),
                            ':id_book'        => $bookId,
                            ':id_category'    => $categoryId,
                        ]);
                    }
                }
                $bookAuthors = $this->connection->fetchAll("SELECT id_author FROM " . $this->referenceBookAuthor . " WHERE id_book = :id_book", [
                    ':id_book'  => $bookId
                ]);
                $existingAuthor = [];
                foreach($bookAuthors as $author) {
                    if(!in_array($author['id_author'], $authorsId)) {
                        $this->connection->commands('DELETE FROM ' . $this->referenceBookAuthor . ' WHERE id_author = :id_author', [
                            ':id_author'  => $author['id_author']
                        ]);
                    }
                    else {
                        array_push($existingAuthor, $author['id_author']);
                    }
                }
                foreach($authorsId as $authorId) {
                    if(!in_array($authorId, $existingAuthor)) {
                        $this->connection->commands("INSERT INTO " . $this->referenceBookAuthor . '(id, id_book, id_author) VALUES(:id, :id_book, :id_author)', [
                            ':id'             => UUIDv4(),
                            ':id_book'        => $bookId,
                            ':id_author'      => $authorId,
                        ]);
                    }
                }
                $this->connection->commands("UPDATE " . $this->tableName . " SET title = :title, barcode = :barcode, id_publisher = :id_publisher, published_year = :published_year, picture = :picture WHERE id = :id", [
                    ':title'            => $data['name'],
                    ':barcode'          => $data['barcode'],
                    ':id_publisher'     => $data['id_publisher'],
                    ':published_year'   => $data['published_year'],
                    ':picture'          => $data['picture'],
                    ':id'               => $bookId
                ]);
                return 1;
            } catch (\Exception $e) {
                return 0;
            }
            // $province = $this->getBookById($id);
            // if(empty($province)) {
            //     return 2;
            // }
            // else {
            //     $province = $this->getBook($data['name']);
            //     if(!empty($province)) {
            //         if($province['id'] != $id) {
            //             return 3;
            //         }
            //     }
            //     $this->connection->commands("UPDATE ". $this->tableName ." SET name = :name WHERE id = :id", [
            //         ':name'        => $data['name'],
            //         ':id'          => $id
            //     ]);
            //     return 1;
            // }
        }

        public function getBookById(string $id, bool $active = false, bool $joinPublisher = false) {
            $sql = "";
            if($joinPublisher) {
                $sql .= "SELECT b.id, b.title, mp.name, b.created_at FROM " . $this->tableName . " b " . " LEFT JOIN " . $this->referencedOnPublisher . " mp ON mp.id = b.id_publisher WHERE b.id = :id";
            }
            else {
                $sql = "SELECT * FROM ". $this->tableName . " b";
                $sql .= " WHERE b.id = :id";
            }
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $book = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $book;
        }   
        
        public function getBookAuthor(string $id) {
            $data = $this->connection->fetchAll("SELECT id_author FROM " . $this->referenceBookAuthor . " WHERE id_book = :id_book", [
                ':id_book'   => $id
            ]);
            return $data;
        }
        
        public function getBookCategory(string $id) {
            $data = $this->connection->fetchAll("SELECT id_category FROM " . $this->referenceBookCategory . " WHERE id_book = :id_book", [
                ':id_book'   => $id
            ]);
            return $data;
        }

        public function deactivateBook(string $id) {
            // $this->connection->commands("DELETE FROM ". $this->tableName ." WHERE id = :id", [":id"=> $id]);
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 0 WHERE id = :id", [":id"=> $id]);
            return 1;
        }

        public function reactivateBook(string $id) {
            $this->connection->commands("UPDATE ". $this->tableName ." SET status = 1 WHERE id = :id", [":id"=> $id]);
            return 1;
        }
    }