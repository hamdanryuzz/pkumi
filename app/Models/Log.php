<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user', 'action', 'module', 'old_data', 'new_data', 'ip_address'
    ];
}
