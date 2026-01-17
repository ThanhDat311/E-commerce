<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function findByIds(array $ids);
}
