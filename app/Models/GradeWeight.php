<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_weight',
        'assignment_weight',
        'midterm_weight',
        'final_weight'
    ];

    public static function getCurrentWeights()
    {
        return self::first() ?? self::create([
            'attendance_weight' => 10,
            'assignment_weight' => 20,
            'midterm_weight' => 30,
            'final_weight' => 40
        ]);
    }

    public function getTotalWeight()
    {
        return $this->attendance_weight + $this->assignment_weight + 
               $this->midterm_weight + $this->final_weight;
    }
}