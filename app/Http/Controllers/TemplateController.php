<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Category;
use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

class TemplateController extends AdminController
{
    public $sorting = true;
    public $model = Template::class;
    public $request = TemplateRequest::class;
    public $ignore = ['categories'];
    public $columns = [
        ['field' => 'id', 'headerName' => 'ID'],
        ['field' => 'title', 'headerName' => 'სათაური'],
        ['field' => 'old_url', 'headerName' => 'ლინკი'],
        ['field' => 'new_url', 'headerName' => 'ახალი ლინკი'],
    ];
    public $fields = [
        [
            'size' => 8,
            'list' => [
                ['type' => 'text', 'name' => 'title', 'label' => 'სახელი'],
                ['type' => 'text', 'name' => 'old_url', 'label' => 'ძველი ლინკი'],
                ['type' => 'text', 'name' => 'new_url', 'label' => 'ახალი ლინკი'],
            ]
        ],
        [
            'size' => 4,
            'list' => [
                ['type' => 'select', 'name' => 'size', 'label' => 'ზომა'],
                ['type' => 'select', 'name' => 'categories', 'label' => 'კატეგორია', 'multiple' => true, 'value' => []],
                ['type' => 'image', 'name' => 'image', 'label' => 'სურათი'],
            ]
        ]
    ];
    public $search = [
        ['name' => 'id', 'type' => 'number', 'label' => 'ID'],
        ['name' => 'title', 'type' => 'text', 'label' => 'სახელი'],
        ['name' => 'old_url', 'type' => 'text', 'label' => 'ლინკი'],
        ['name' => 'new_url', 'type' => 'text', 'label' => 'ახალი ლინკი'],
    ];
    public $fileFilds = ['image'];

    public function __construct()
    {
        $this->fields[1]['list'][0]['options'] = [
            ['text' => 'Small', 'value' => 0],
            ['text' => 'Medium', 'value' => 1],
            ['text' => 'Large', 'value' => 2],
        ];
        $this->fields[1]['list'][1]['options'] = Category::whereNotNull('category_id')->get()->map(function (Category $category) {
            return [
                'text' => $category->title,
                'value' => $category->id,
            ];
        });

        parent::__construct();
    }

    protected function afterCreate(Model $model)
    {
        $model->categories()->sync(request()->input('categories', []));
    }

    protected function afterUpdate(Model $model)
    {
        $model->categories()->sync(request()->input('categories', []));
    }
}

// s 35
// m 49
// l 69

// ena 20
// brendingi 500
