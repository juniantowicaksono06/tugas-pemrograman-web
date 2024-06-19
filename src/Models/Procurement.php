<?php
    namespace Models;
    class Procurement extends Model {
        private $tableName = "procurement";
        private $stockTable = "book_stock";
        private $procurementDetailTable = "procurement_detail";

        public function getProcurements() {
            $user = $this->connection->fetchAll("SELECT * FROM ". $this->tableName ."");
            return $user;
        }

        public function createProcurement($data) {
            $bookStock = [];
            $procurementDetail = [];
            $idProcurement = UUIDv4();
            for ($i = 0; $i < count($data['booksId']); $i++) {
                $idStock = UUIDv4();
                $bookStock[] = [
                    $idStock,
                    $data['booksId'][$i],
                    $data['jumlah'][$i]
                ];
                $procurementDetail[] = [
                    UUIDv4(),
                    $idProcurement,
                    $idStock
                ];
            }
            try {
                $this->connection->beginTransaction();
                $this->connection->batchInsert($this->stockTable, $bookStock, ['`id`', '`book_id`', '`in`']);
                
                $this->connection->commands("INSERT INTO " . $this->tableName . "(id, date_procurement, created_by) VALUES(:id, :date_procurement, :created_by)", [
                    ':id'               => $idProcurement,
                    ':date_procurement' => date('Y-m-d H:i:s'),
                    ':created_by'       => $_SESSION['admin_credential']['id']
                ]);
                $this->connection->batchInsert($this->procurementDetailTable, $procurementDetail, ['`id`', '`procurement_id`', '`id_stock`']);
                
                $this->connection->commit();
                return 1;   
            } catch (\Exception $e) {
                $this->connection->rollBack();
                return 0;
            }
        }
    }