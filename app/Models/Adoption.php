<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DONE = 'done';

    protected $fillable = [
        'pet_id',
        'user_id',
        'status',
        'notes'
    ];

    // 定义与 Pet 模型的关系
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    // 定义与 User 模型的关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
