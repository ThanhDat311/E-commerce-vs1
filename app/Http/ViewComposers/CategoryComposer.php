<?php

namespace App\Http\ViewComposers;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\View\View;

class CategoryComposer
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function compose(View $view)
    {
        $view->with('categories', $this->categoryRepo->getTree());
    }
}
