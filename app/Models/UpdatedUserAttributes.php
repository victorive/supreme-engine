<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdatedUserAttributes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attributes',
        'status'
    ];

    protected $casts = [
        'attributes' => 'array'
    ];
}
