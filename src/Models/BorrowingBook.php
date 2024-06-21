<?php
    namespace Models;
    class BorrowingBook extends Model {
        private $tableName = "borrowing_book";
        private $stockTable = "book_stock";
        private $detailTable = "borrowing_detail";

        public function getBorrowings() {
            $borrowing = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $borrowing;
        }

        public function createBorrow($data, $dateBorrow, $borrower) {
            $bookStock = [];
            $borrowingDetail = [];
            $idBorrow = UUIDv4();
            for ($i = 0; $i < count($data['booksId']); $i++) {
                $dataStock = $this->connection->fetchOne("SELECT SUM(`in`) AS `stock`, `book_id` ".$this->stockTable." FROM book_stock WHERE book_id = :book_id GROUP BY book_id HAVING `stock` > 0 FOR UPDATE", [
                    ':book_id'    => $data['booksId'][$i],
                ]);
                if(empty($dataStock)) {
                    return 3;
                }
                $idStock = UUIDv4();
                $bookStock[] = [
                    $idStock,
                    $data['booksId'][$i],
                    1
                ];
                $borrowingDetail[] = [
                    UUIDv4(),
                    $idBorrow,
                    $idStock
                ];
            }
            try {
                $currentDate = date('Y-m-d');
                $startDate = $currentDate . " 00:00:00";
                $endDate = $currentDate . " 23:59:59";
                $dataBorrow = $this->connection->fetchOne("SELECT borrow_code FROM " . $this->tableName . " WHERE date_borrow BETWEEN :startDate AND :endDate FOR UPDATE", [
                    ':startDate'    => $startDate,
                    ':endDate'      => $endDate
                ]);

                if(!empty($dataBorrow)) {
                    $borrowCodeSplit = explode('-', $dataBorrow['borrow_code']);
                    $code = end($borrowCodeSplit);
                    $code = intval($code);
                    $code += 1;
                    $code = str_pad($code, 4, '0', STR_PAD_LEFT);
                    $borrowCodeSplit[count($borrowCodeSplit) - 1] = $code;
                    $borrowCode = join('-', $borrowCodeSplit);
                }
                else {
                    $currentDate = date('Ymd');
                    $borrowCode = "PMJ-" . $currentDate . "-0001";
                }

                $currentDate = new \DateTime();
                $currentDate->modify('+7 days');
                $dueDate = $currentDate->format('Y-m-d');
                $dueDate = $dueDate . " 23:59:59";

                

                $this->connection->beginTransaction();
                $insertStock = $this->connection->batchInsert($this->stockTable, $bookStock, ['`id`', '`book_id`', '`out`']);
                if($insertStock === false) {
                    throw new \Exception("Error inserting stock");
                }
                
                $insertBorrow = $this->connection->commands("INSERT INTO " . $this->tableName . "(id, member_id, borrow_code, date_borrow, due_date, created_by) VALUES(:id, :member_id, :borrow_code, :date_borrow, :due_date, :created_by)", [
                    ':id'               => $idBorrow,
                    ':member_id'        => $borrower,
                    ':borrow_code'      => $borrowCode,
                    ':date_borrow'      => $dateBorrow,
                    ':due_date'         => $dueDate,
                    ':created_by'       => $_SESSION['admin_credential']['id']
                ]);
                if($insertBorrow === false) {
                    throw new \Exception("Error inserting borrow book");
                }
                $insertDetail = $this->connection->batchInsert($this->detailTable, $borrowingDetail, ['`id`', '`borrowing_id`', '`id_stock`']);
                
                if($insertDetail === false) {
                    throw new \Exception("Error inserting borrow borrow detail");
                }
                
                $this->connection->commit();
                return 1;   
            } catch (\Exception $e) {
                $this->connection->rollBack();
                return 0;
            }
        }
    }