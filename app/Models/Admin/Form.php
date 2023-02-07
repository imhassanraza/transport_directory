<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = 'forms';
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
