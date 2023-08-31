<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailText extends Model
{
    protected $table = 'email_texts'; // Table name

    protected $fillable = ['name', 'content']; // Fillable attributes

    // Retrieve content by name
    public static function getContentByName($name)
    {
        return self::where('name', $name)->value('content');
    }
}
