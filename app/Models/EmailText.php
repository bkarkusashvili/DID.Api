<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailText extends Model
{
    protected $table = 'email_texts'; 

    protected $fillable = ['name', 'content']; 

    public static function getContentByName($name)
    {
        return self::where('name', $name)->value('content');
    }
}
