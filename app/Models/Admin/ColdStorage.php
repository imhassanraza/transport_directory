<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColdStorage extends Model
{
    use HasFactory;
    protected $table = 'cold_storages';
    protected $guard = 'admin';
    protected $fillable = [
        'store_name',
        'city_id',
        'address',
        'owner_name',
        'owner_phone',
        'manager_name',
        'manager_phone',
        'pin_location',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
