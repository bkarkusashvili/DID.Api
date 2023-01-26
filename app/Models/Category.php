<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $guarded = [];
    protected $appends = ['small', 'medium', 'large'];

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class);
    }

    public function getSmallAttribute()
    {
        return $this->templates()->where('size', 0)->first();
    }

    public function getMediumAttribute()
    {
        return $this->templates()->where('size', 1)->first();
    }

    public function getLargeAttribute()
    {
        return $this->templates()->where('size', 2)->first();
    }
}
