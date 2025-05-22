<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nit',
        'name',
        'address',
        'phone',
        'active'
    ];

    protected $attributes = [
        'active' => true,
    ];

    protected function casts()
    {
        return [
            'active' => 'boolean'
        ];
    }
}
