<?php
include_once __DIR__ . '/../includes/alert_message.php';
include __DIR__ . '/../../../config/init.php';
$database = new Database();
$connection = $database->getConnection();
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'account';
?>
<header class="header_account-page">

    <a class="<?php echo ($tab == 'list') ? 'active' : '' ?>" href="index.php?page=import_manager&tab=list">Danh sách phiếu nhập</a>
    <span>/</span>
    <a class="<?php echo ($tab == 'create') ? 'active' : '' ?>" href="index.php?page=import_manager&tab=create">Tạo phiếu nhập </a>

</header>

<div class="content_account-page">
    <div class="tab-content">

        <?php
        if (isset($_GET['page']) && $_GET['page'] === 'import_manager' && isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            switch ($tab) {
                case 'list':
                    include __DIR__ . '/../includes/import-action/list_import.php';
                    break;
                case 'create':
                    include __DIR__ . '/../includes/import-action/create_import.php';
                    break;
                default:
                    include __DIR__ . '/../includes/import-action/list_import.php';
                    break;
            }
        } else {
            include __DIR__ . '/../includes/import-action/list_import.php';
        }
        ?>

    </div>