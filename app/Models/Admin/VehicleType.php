<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'vehicle_types';
    protected $guard = 'admin';
    protected $fillable = [
        'vehicle_type',
        'capacity',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}