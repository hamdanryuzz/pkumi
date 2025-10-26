<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Year extends Model
{
    use HasFactory;

    protected $fillable = ['period_id', 'name'];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class);
    }
}
