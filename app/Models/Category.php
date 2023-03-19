<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $guarded = [];

    // $size = [
    //     0 => 'small',
    //     1 => 'medium',
    //     2 => 'large',
    // ];

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
