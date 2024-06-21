<?php
    namespace Models;
    use Exception;
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
                $currentDate = date('Y-m-d');
                $startDate = $currentDate . " 00:00:00";
                $endDate = $currentDate . " 23:59:59";
                $dataProcurement = $this->connection->fetchOne("SELECT procurement_code FROM " . $this->tableName . " WHERE created_at BETWEEN :startDate AND :endDate FOR UPDATE", [
                    ':startDate'    => $startDate,
                    ':endDate'      => $endDate
                ]);

                if(!empty($dataProcurement)) {
                    $procurementCodeSplit = explode('-', $dataProcurement['procurement_code']);
                    $code = end($procurementCodeSplit);
                    $code = intval($code);
                    $code += 1;
                    $code = str_pad($code, 4, '0', STR_PAD_LEFT);
                    $procurementCodeSplit[count($procurementCodeSplit) - 1] = $code;
                    $procurementCode = join('-', $procurementCodeSplit);
                }
                else {
                    $currentDate = date('Ymd');
                    $procurementCode = "PND-" . $currentDate . "-0001";
                }

                $insertStock = $this->connection->batchInsert($this->stockTable, $bookStock, ['`id`', '`book_id`', '`in`']);
                if($insertStock === false) {
                    throw new Exception("Error inserting stock");
                } 
                
                $insertProcurement = $this->connection->commands("INSERT INTO " . $this->tableName . "(id, procurement_code, date_procurement, created_by) VALUES(:id, :procurement_code, :date_procurement, :created_by)", [
                    ':id'               => $idProcurement,
                    ':procurement_code' => $procurementCode,
                    ':date_procurement' => date('Y-m-d H:i:s'),
                    ':created_by'       => $_SESSION['admin_credential']['id']
                ]);

                if($insertProcurement === false) {
                    throw new Exception("Error inserting procurement");
                }


                $detail = $this->connection->batchInsert($this->procurementDetailTable, $procurementDetail, ['`id`', '`procurement_id`', '`id_stock`']);

                if($detail === false) {
                    throw new Exception("Error inserting procurement detail");
                }

                $this->connection->commit();
                return 1;   
            } catch (Exception $e) {
                $this->connection->rollBack();
                return 0;
            }
        }
    }