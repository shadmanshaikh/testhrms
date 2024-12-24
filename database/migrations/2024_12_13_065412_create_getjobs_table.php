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
        Schema::create('getjobs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('department');
                $table->string('type');
                $table->string('location');
                $table->text('description');
                $table->text('requirements');
                $table->string('salary_range');
                $table->string('status');
                $table->date('deadline');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('getjobs');
    }
};
