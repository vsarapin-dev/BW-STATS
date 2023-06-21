<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'user_id',
        'shared_with',
    ];

    protected $casts = [
        'shared_with' => 'array'
    ];
}
