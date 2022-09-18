<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends AdminController
{
    public $sorting = true;
    public $model = Category::class;
    public $request = CategoryRequest::class;
    public $columns = [
        ['field' => 'id', 'headerName' => 'ID'],
        ['field' => 'title', 'headerName' => 'სახელი'],
    ];
    public $fields = [
        [
            'size' => 12,
            'list' => [
                ['type' => 'text', 'name' => 'title', 'label' => 'სახელი'],
            ]
        ]
    ];
    public $search = [
        ['name' => 'id', 'type' => 'number', 'label' => 'ID'],
        ['name' => 'title', 'type' => 'text', 'label' => 'სახელი'],
    ];

    public function index($query = null)
    {
        $query = $this->model::query()->whereNull('category_id');

        return parent::index($query);
    }
}
