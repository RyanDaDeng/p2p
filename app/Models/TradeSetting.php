<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_toc',
        'welcome_message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}