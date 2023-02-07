<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'phone_no',
        'cnic',
        'city_id',
        'vehicle_id',
        'photo',
        'status',
        'is_thief',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
