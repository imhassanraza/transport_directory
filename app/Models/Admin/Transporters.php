<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporters extends Model
{
    use HasFactory;
    protected $table = 'transporters';
    protected $guard = 'admin';
    protected $fillable = [
        'transporter_name',
        'phone_no',
        'city_id',
        'total_vehicle',
        'type',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
