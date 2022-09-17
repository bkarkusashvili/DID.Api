<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Category;
use App\Models\Template;

class TemplateController extends AdminController
{
    public $sorting = true;
    public $model = Template::class;
    public $request = TemplateRequest::class;
    public $columns = [
        ['field' => 'id', 'headerName' => 'ID'],
        ['field' => 'title', 'headerName' => 'სათაური'],
    ];
    public $fields = [
        [
            'size' => 8,
            'list' => [
                ['type' => 'text', 'name' => 'title', 'label' => 'სახელი'],
                ['type' => 'text', 'name' => 'old_url', 'label' => 'ძველი ლინკი'],
            ]
        ],
        [
            'size' => 4,
            'list' => [
                ['type' => 'number', 'name' => 'number', 'label' => 'ნომერი'],
                ['type' => 'select', 'name' => 'size', 'label' => 'ზომა'],
                ['type' => 'select', 'name' => 'category_id', 'label' => 'კატეგორია', 'multiple' => true],
                ['type' => 'image', 'name' => 'image', 'label' => 'სურათი'],
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

        $this->fields[1]['list'][1]['options'] = [
            ['text' => 'Small', 'value' => 0],
            ['text' => 'Medium', 'value' => 1],
            ['text' => 'Large', 'value' => 2],
        ];
        $this->fields[1]['list'][2]['options'] = Category::all()->map(function (Category $category) {
            return [
                'text' => $category->title,
                'value' => $category->id,
            ];
        });

        parent::__construct();
    }
}

// s 35
// m 49
// l 69

// ena 20
// brendingi 500
