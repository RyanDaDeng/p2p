<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING_STATUS = 0;
    const REGISTERED_STATUS = 1;
    const DECLINED_STATUS = 2;
    const UNKNOWN_STATUS = -1;

    const FAILURE_STATUS = -2;
    protected $fillable = [
        'user_id',
        'chain',
        'currency',
        'currency_key',
        'network',
        'chain_label',
        'address',
        'is_verified',
        'fireblocks_status',
        'fireblocks_status_ref',
        'fireblocks_data',
        'fireblocks_report',
        'status'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateFireBlocksUserId($userId){
        $prefix = config('fireblocks.sandbox') === true ? 'TEST' : 'PROD';
        return 'AUS_' .$prefix . $userId;
    }
}
