<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../model/Model/Model_Import.php';


class ImportController
{
    private $importModel;

    public function __construct()
    {
        global $connection;
        $this->importModel = new Model_import($connection);
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
}