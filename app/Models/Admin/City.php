<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
