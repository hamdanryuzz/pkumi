<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'name',
        'email',
        'phone',
        'address',
        'status'
    ];

    public function grade()
    {
        return $this->hasOne(Grade::class);
    }
}