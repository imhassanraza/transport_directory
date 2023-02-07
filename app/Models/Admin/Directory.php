<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    use HasFactory;
    protected $table = 'directories';
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'phone_no',
        'city_id',
        'form_id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
