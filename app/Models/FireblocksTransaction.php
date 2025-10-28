<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireblocksTransaction extends Model
{
    use HasFactory;

    protected $guarded = [
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];
}
