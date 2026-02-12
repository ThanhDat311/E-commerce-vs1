<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function all();

    public function getFeaturedCategories($limit = 6);

    public function getTree(); // For Mega Menu
}
