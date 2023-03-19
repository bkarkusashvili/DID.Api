<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    public $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
