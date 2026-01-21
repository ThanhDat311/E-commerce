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
}