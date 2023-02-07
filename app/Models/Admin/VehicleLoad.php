<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleLoad extends Model
{
    use HasFactory;
    protected $table = 'bilties';
    protected $guard = 'admin';
    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'source_city',
        'destination_city',
        'guarantor_detail',
        'bilty_details',
        'bilty_date',
        'status',
        'created_at',
        'updated_at',
        'bilty_image',
        'driver_image',
        'bilty_number',
        'bilty_insurance',
        'created_by',
        'updated_by'
    ];
}
