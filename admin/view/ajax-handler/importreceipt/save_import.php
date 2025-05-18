<?php
include_once __DIR__ . '/../../../controller/ImportController.php';
$supplier_id = $_POST['supplier_id'] ?? null;
$details_json = $_POST['details'] ?? null;

if (!$supplier_id || !$details_json) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ hoặc thiếu']);
    exit;
}
$created_at = date('Y-m-d H:i:s');
$updated_at = $created_at;
$details = json_decode($details_json, true);
if ($details === null) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu chi tiết không hợp lệ']);
    exit;
}

$importController = new ImportController();
$result = $importController->saveImportReceipt(1, $supplier_id, $details, $created_at, $updated_at);

if ($result['success']) {
    echo json_encode(['success' => true, 'import_id' => $result['import_id']]);
} else {
    echo json_encode(['success' => false, 'message' => $result['message']]);
}
