<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function createOrder(array $data);

    public function createOrderItem(array $data);

    public function getAllOrders($perPage = 10);

    public function find($id);

    public function getProductsByVendor($vendorId, $perPage = 10);

    // Dashboard Methods
    // Dashboard Methods
    public function getTotalRevenue($vendorId = null);

    public function count();

    public function getPendingCount();

    public function getLatestOrders($limit = 5, $vendorId = null);

    public function getRevenueData($days = 7, $vendorId = null);

    public function getVendorRevenue($vendorId);
}
