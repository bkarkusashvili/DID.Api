<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'keywords' => 'array',
        'photos' => 'array',
        'materials' => 'array',
    ];

    static $types = [
        0 => 'Apply Emojis',
        1 => 'Apply Logo',
        2 => 'Apply Branding Style',
        3 => 'Generate Text on Photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
