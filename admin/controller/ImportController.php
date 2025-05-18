<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Import.php';
include_once __DIR__ . '/../model/Model/Model_ImportDetail.php';


class ImportController
{
    private $importModel;
    private $importDetailModel;

    public function __construct()
    {
        global $connection;
        $this->importModel = new Model_import($connection);
        $this->importDetailModel = new Model_importdetail($connection);
    }

    public function getListImports(array $filters = [], int $perPage = 5, int $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        $imports = $this->importModel->filterImports($filters, $perPage, $offset);
        $totalRecords = $this->importModel->countFilteredImports($filters);

        $totalPages = ceil($totalRecords / $perPage);

        return [
            'imports' => $imports,
            'totalPages' => $totalPages
        ];
    }
    public function saveImportReceipt($user_id, $supplier_id, $details,$created_at,$updated_at)
    {
        try {
            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            // Tính tổng giá
            $total_price = 0;
            foreach ($details as $detail) {
                $total_price += $detail['quantity'] * $detail['price'];
            }

            $sale_price = $total_price * 1.2; // hệ số 20% lợi nhuận

            // Tạo phiếu nhập
            $import_id = $this->importModel->createImport(
                $user_id,
                $supplier_id,
                $total_price,
                $created_at,
                $updated_at
            );

            if (!$import_id) {
                throw new Exception("Tạo phiếu nhập thất bại");
            }

            // Tạo từng chi tiết phiếu nhập
            foreach ($details as $detail) {
                $success = $this->importDetailModel->createImportDetail(
                    $import_id,
                    $detail['product_detail_id'],
                    $detail['quantity'],
                    $detail['price'],
                    $detail['price']*1.2,
                    $created_at,
                    $updated_at
                );

                if (!$success) {
                    throw new Exception("Tạo chi tiết phiếu nhập thất bại");
                }
            }

            return ['success' => true, 'import_id' => $import_id];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}