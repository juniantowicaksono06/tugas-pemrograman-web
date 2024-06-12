<?php
    require_once('./models/Model.php');
    class MasterBook extends Model {
        private $tableName = "master_book";
        private $referencedOnPublisher = 'master_publisher';
        private $referencedOnAuthor = 'master_author';
        private $referencedOnCategory = 'master_category';
        private $referenceBookCategory = 'book_category';
        
        public function getBook(string $name, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE name = :name ";
            if ($active) {
                $sql .= " AND status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':name'  => $name]);
            return $publisher;
        }
        public function getBooks() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function createNewBook(array $data) {
            try {
                $author = $this->connection->fetchOne("SELECT * FROM " . $this->referencedOnAuthor . " WHERE id = :id_author", [
                    ':id_author'    => $data['id_author']
                ]);
                $publisher = $this->connection->fetchOne("SELECT * FROM " . $this->referencedOnPublisher . " WHERE id = :id_publisher", [
                    ':id_publisher'    => $data['id_publisher']
                ]);
                if(empty($author)) {
                    return 3;
                }
                if(empty($publisher)) {
                    return 4;
                }
                $bookId = UUIDv4();
                $categoriesId = json_decode($data['id_category']);
                $this->connection->commands("INSERT INTO ". $this->tableName ." (id, title, barcode, id_publisher, publish_date, picture, status) 
                VALUES(:id, :title, :barcode, :id_publisher, :publish_date, :picture, :status)", [
                    ':id'           => $bookId,
                    ':title'        => $data['name'],
                    ':id_publisher' => $data['id_publisher'],
                    ':publish_date' => $data['publish_date'],
                    ':barcode'      => $data['barcode'],
                    ':picture'      => $data['picture'],
                    ':status'       => 1,
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
                return 1;
            }
            catch(\Exception $e) {
                return 0;
            }
        }

        public function editBook(string $id, array $data) {
            $province = $this->getBookById($id);
            if(empty($province)) {
                return 2;
            }
            else {
                $province = $this->getBook($data['name']);
                if(!empty($province)) {
                    if($province['id'] != $id) {
                        return 3;
                    }
                }
                $this->connection->commands("UPDATE ". $this->tableName ." SET name = :name WHERE id = :id", [
                    ':name'        => $data['name'],
                    ':id'          => $id
                ]);
                return 1;
            }
        }

        public function getBookById(string $id, bool $active = false) {
            $sql = "SELECT * FROM ". $this->tableName ." WHERE id = :id";
            if ($active) {
                $sql .= " AND user_status = 1";
            }
            $publisher = $this->connection->fetchOne($sql, [':id'  => $id]);
            return $publisher;
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