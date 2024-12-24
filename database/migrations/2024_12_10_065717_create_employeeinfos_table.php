<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employeesinfos', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('gender', ['1', '2']);
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('dob');
            $table->text('address');
            $table->string('emergency_contact');
            $table->foreignId('department_id')->constrained();
            $table->string('employee_id')->unique();
            $table->string('designation');
            $table->date('joining_date');
            $table->string('work_location');
            $table->enum('employment_type', ['1', '2', '3']);
            $table->string('emirates_id')->nullable();
            $table->string('passport')->nullable();
            $table->string('work_permit')->nullable();
            $table->string('certificates')->nullable();
            $table->string('police_clearance')->nullable();
            $table->string('medical_certificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
