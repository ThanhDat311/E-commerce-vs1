<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findByIds(array $ids);

    public function getFilteredProducts(array $filters, int $perPage = 9);

    public function getCategoriesWithProductCount();

    public function getProductDetails(int $id);

    public function getRelatedProducts($currentProduct, int $limit = 4);

    /**
     * Get home page products (latest & new arrivals) with Redis caching
     * TTL: 60 minutes | Cache Key: home_products
     */
    public function getHomePageProducts(int $limit = 8);

    public function getLowStockCount(int $threshold = 10);

    public function filterAndSort(array $filters, int $perPage = 12);

    public function findBySlug(string $slug);
}
