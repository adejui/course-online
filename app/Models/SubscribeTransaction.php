<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscribeTransaction extends Model
{
    use HasFactory, SoftDeletes;
    // cara pertama dalam mempersiapkan mass assigment
    protected $fillable = [
        'total_amount',
        'is_paid',
        'subscription_start_date',
        'proof',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
