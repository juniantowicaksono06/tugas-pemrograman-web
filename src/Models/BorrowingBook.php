<?php
    namespace Models;
    class BorrowingBook extends Model {
        private $tableName = "borrowing_book";
        private $stockTable = "book_stock";
        private $detailTable = "borrowing_detail";
        private $memberTable = "master_member";
        private $adminTable = "master_admin";
        private $fineTable = "setting_fine";

        public function getBorrowings() {
            $borrowing = $this->connection->fetchAll("SELECT bb.date_borrow, bb.id AS id, mm.fullname, bb.due_date, bb.date_return, ma1.fullname AS admin_accept, ma2.fullname AS admin_receive, bb.denda FROM ". $this->tableName ." bb LEFT JOIN " . $this->memberTable . " mm ON mm.id = bb.member_id LEFT JOIN " . $this->adminTable . " ma1 ON ma1.id = bb.created_by LEFT JOIN " . $this->adminTable . " ma2 ON ma2.id = bb.updated_by");
            return $borrowing;
        }
        

        public function getBorrowingsFines() {
            $borrowing = $this->connection->fetchAll("SELECT bb.date_borrow, bb.id AS id, mm.fullname, bb.due_date, bb.date_return, ma1.fullname AS admin_accept, ma2.fullname AS admin_receive, bb.denda FROM ". $this->tableName ." bb LEFT JOIN " . $this->memberTable . " mm ON mm.id = bb.member_id LEFT JOIN " . $this->adminTable . " ma1 ON ma1.id = bb.created_by LEFT JOIN " . $this->adminTable . " ma2 ON ma2.id = bb.updated_by WHERE bb.denda > 0");
            return $borrowing;
        }

        public function getTotalBorrowing(string $id) {
            $borrowing = $this->connection->fetchOne("SELECT COUNT(member_id) as total FROM " . $this->tableName . " WHERE member_id = :member_id", [
                ':member_id'       => $id
            ]);
            return $borrowing;
        }

        public function getTotalFines(string $id) {
            $borrowing = $this->connection->fetchOne("SELECT SUM(denda) as total FROM " . $this->tableName . " WHERE member_id = :member_id AND date_return IS NULL", [
                ':member_id'       => $id
            ]);
            $borrowing2 = $this->connection->fetchAll("SELECT * FROM " . $this->tableName . " WHERE member_id = :member_id AND date_return IS NULL", [
                ':member_id'       => $id
            ]);
            
        
            $fines = $this->connection->fetchOne("SELECT denda  FROM ". $this->fineTable);
            if(empty($fines)) {
                $fines = [
                    'denda'     => 1000,
                ];
            }
            $totalFines['total'] = $borrowing['total'];
            $date1 = new \DateTime();
            foreach($borrowing2 as $fine) {
                $date2 = new \DateTime($fine['due_date']);
                if($date1 > $date2) {
                    $interval = $date1->diff($date2);
                    $daysDifference = $interval->days + 1;
                    $totalFines['total'] = $fines['denda'] * $daysDifference;
                }
            }
            if(empty($totalFines['total'])) {
                $totalFines['total'] = 0;
            }
            return $totalFines;
        }

        public function getBorrowingById(string $id) {
            $sql = "SELECT bb.*, ad1.fullname AS borrowed_from, ad2.fullname AS received_by, mm.fullname AS borrower_name 
            FROM " . $this->tableName . " bb 
            LEFT JOIN ".$this->memberTable." mm ON mm.id = bb.member_id 
            LEFT JOIN ".$this->adminTable." ad1 ON ad1.id = bb.created_by
            LEFT JOIN ".$this->adminTable." ad2 ON ad2.id = bb.updated_by
            WHERE bb.id = :id";
            $borrowing = $this->connection->fetchOne($sql, [
                ':id'       => $id
            ]);
            return $borrowing;
        }
        
        public function returnBook(string $id) {
            $borrowing = $this->getBorrowingById($id);
            if(empty($borrowing)) {
                return 2;
            }
            $date1 = new \DateTime();
            if(empty($borrowing['date_return'])) {
                $date2 = new \DateTime($borrowing['due_date']);
            }
            else {
                return 3;
            }
            $denda = 0;
            $settingFines = new SettingFines();
            $fines = $settingFines->getFines();
            if($date1 > $date2) {
                $interval = $date1->diff($date2);
                $daysDifference = $interval->days + 1;
                $denda = $fines['denda'] * $daysDifference;
            }
            $dateReturn = $date1->format('Y-m-d H:i:s');
            $update = $this->connection->commands("UPDATE " . $this->tableName . " SET denda = :denda, date_return = :date_return, updated_by = :updated_by WHERE id = :id", [
                ':denda'            => $denda,
                ':date_return'      => $dateReturn,
                ':id'               => $id,
                ':updated_by'       => $_SESSION['admin_credential']['id']
            ]);
            if($update === false) {
                return 0;
            }
            return 1;
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

                $currentDate = new \DateTime($dateBorrow);
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