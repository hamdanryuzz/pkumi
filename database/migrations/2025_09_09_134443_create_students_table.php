<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_class_id')->constrained('student_classes')->onDelete('cascade');
            $table->foreignId('year_id')->constrained('years')->onDelete('cascade');
            $table->string('username')->unique()->nullable();
            $table->string('nim')->unique();
            $table->string('name');
            $table->string('password')->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('student_job')->nullable();
            $table->enum('marital_status', ['Belum Kawin', 'Kawin'])->nullable();
            $table->string('program')->nullable();
            $table->string('admission_year')->nullable();
            $table->string('first_semester')->nullable();
            $table->enum('status', ['active', 'inactive', 'lulus'])->default('active');
            $table->string('origin_of_university')->nullable();
            $table->string('initial_study_program')->nullable();
            $table->string('graduation_year')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_last_education')->nullable();
            $table->string('father_job')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_last_education')->nullable();
            $table->string('mother_job')->nullable();
            $table->text('address')->nullable();
            $table->string('street')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('village')->nullable(); // atau 'sub_village' jika ingin lebih spesifik
            $table->string('district')->nullable(); // terjemahan dari "Kecamatan"
            $table->string('city')->nullable(); // terjemahan dari "Kab/Kota"
            $table->string('province')->nullable();
            $table->text('description')->nullable(); // untuk "Keterangan"
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};