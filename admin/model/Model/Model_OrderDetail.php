<?php
include_once __DIR__ . '/../Entity/OrderDetail.php';
include_once __DIR__ . '/../../../config/init.php';

class Model_OrderDetail
{
    
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }
 
}