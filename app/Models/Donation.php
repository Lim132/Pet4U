<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'stripe_payment_id',
        'donor_name',
        'donor_email',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 