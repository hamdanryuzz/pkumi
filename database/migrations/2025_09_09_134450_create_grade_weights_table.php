<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grade_weights', function (Blueprint $table) {
            $table->id();
            $table->decimal('attendance_weight', 5, 2)->default(10.00);
            $table->decimal('assignment_weight', 5, 2)->default(20.00);
            $table->decimal('midterm_weight', 5, 2)->default(30.00);
            $table->decimal('final_weight', 5, 2)->default(40.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade_weights');
    }
};