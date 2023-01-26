<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Models\Site;
use App\Models\User;

class SiteController extends AdminController
{
    public $sorting = true;
    public $model = Site::class;
    public $request = SiteRequest::class;
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

    public function __construct()
    {
        $this->fields[1]['list'][0]['options'] = User::whereNull('category_id')->get()->map(function (User $user) {
            return [
                'text' => $user->title,
                'value' => $user->id,
            ];
        });

        parent::__construct();
    }
}
