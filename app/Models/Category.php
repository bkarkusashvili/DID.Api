<?php

namespace App\Models;

use App\Models\Category as ModelsCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $guarded = [];

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class);
    }
}
