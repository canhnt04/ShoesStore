<?php
include_once __DIR__ . '/../model/Model/Model_Order.php';
include_once __DIR__ . '/../../config/init.php';


class OrderController
{
    private $orderModel;

    public function __construct($connection)
    {
        $this->orderModel = new Model_Order($connection);
    }

    public function listOrders(array $filters = [], int $perPage = 5, int $currentPage = 1)
    {
        $offset = ($currentPage - 1) * $perPage;

        $orders = $this->orderModel->getOrdersPaginated($filters, $perPage, $offset);
        $totalOrders = $this->orderModel->countFilteredOrders($filters);
        $totalPages = $perPage > 0 ? ceil($totalOrders / $perPage) : 1;

        return [
            'orders' => $orders,
            'totalPages' => $totalPages,
            'totalCount' => $totalOrders,
        ];
    }


    public function countAllOrders(): int
    {
        return $this->orderModel->countAllOrders();
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
    
    public function getDetailsByOrderId($orderId)
    {
        return $this->orderModel->getDetailsByOrderId($orderId);
    }
    public function getProductQuantity($orderId)
    {
        return $this->orderModel->getProductQuantity($orderId);
    }
    public function updateAmountProduct($orderId)
    {
        return $this->orderModel->updateAmountProduct($orderId);
    }
    public function restoreAmountProduct($orderId)
    {
        return $this->orderModel->restoreAmountProduct($orderId);
    }
}
