<?php
include_once __DIR__ . '/../../config/database/ConnectDB.php';
include_once __DIR__ . '/../controller/model/Model/Model_Order.php';


class OrderController
{
    private $orderModel;

    public function __construct()
    {
        global $connection; // Sử dụng biến toàn cục
        $this->orderModel = new Model_Order($connection);
    }

    public function listOrders()
    {
        // Lấy danh sách đơn hàng từ model
        return $this->orderModel->getAllOrders();
        // include __DIR__ . '/../view/includes/account-action/account_action.php';
    }
    public  function getOrderById($orderId)
    {
       return $this->orderModel->getOrdersById($orderId);
    }
    public  function getOrdersByCustomerIdAndDateRange($customerId, $beginDate, $endDate)
    {
        return $this->orderModel->getOrdersByCustomerIdAndDateRange($customerId, $beginDate, $endDate);
    }

    public function updateOrderStatus($orderId, $newStatus)
    {

        return $this->orderModel->updateStatus($orderId, $newStatus);
    }
    public function filterOrders($status, $beginDate,$endDate,$district,$province)
    {
        // Gọi phương thức filterOrders từ Model_Order để lấy danh sách đơn hàng đã lọc
        return $this->orderModel->filterOrders($status, $beginDate,$endDate, $district,$province);
    }
    public function getOrdersByOrderId($orderId)
    {
        return $this->orderModel->getOrdersByOrderId($orderId);
    }
}
?>