<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class SubCategoryController extends AdminController
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
            'size' => 8,
            'list' => [
                ['type' => 'text', 'name' => 'title', 'label' => 'სახელი'],
            ]
        ],
        [
            'size' => 4,
            'list' => [
                ['type' => 'select', 'name' => 'category', 'label' => 'კატეგორია'],
            ]
        ]
    ];
    public $search = [
        ['name' => 'id', 'type' => 'number', 'label' => 'ID'],
        ['name' => 'title', 'type' => 'text', 'label' => 'სახელი'],
    ];
    public $fileFilds = ['image'];

    public function __construct()
    {

        $this->fields[1]['list'][0]['options'] = Category::whereNull('category_id')->get()->map(function (Category $category) {
            return [
                'text' => $category->title,
                'value' => $category->id,
            ];
        });

        parent::__construct();
    }
}
