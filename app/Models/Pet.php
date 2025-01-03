<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    // 定义可填充的字段，防止批量赋值漏洞
    protected $fillable = [
        'name',
        'age',
        'species',
        'breed',
        'gender',
        'color',
        'size',
        'vaccinated',
        'healthStatus',
        'personality',
        'description',
        'photos',
        'videos',
        'addedBy',
        'addedByRole',
        'verified',
        'adopted'
    ];

    protected $casts = [
        'healthStatus' => 'array',
        'photos' => 'array',
        'videos' => 'array',
        'vaccinated' => 'boolean',
        'verified' => 'boolean',
        'adopted' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'addedBy');
    }

    // 添加与 Adoption 的关系
    public function adoptions()
    {
        return $this->hasMany(Adoption::class);
    }
}
