<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyPet extends Model
{
    protected $fillable = [
        'user_id',
        'pet_id',
        'adoption_id',
        'pet_photos',
        'pet_name',
        'pet_breed',
        'pet_gender',
        'pet_age',
        'pet_size',
        'pet_color',
        'pet_description',
        'pet_area',
        'owner_name',
        'owner_email',
        'owner_phone',
        'qr_code_path',
        'show'
    ];

    // 定义哪些属性应该被转换
    protected $casts = [
        'pet_photos' => 'array',
        'show' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function adoption()
    {
        return $this->belongsTo(Adoption::class);
    }
}