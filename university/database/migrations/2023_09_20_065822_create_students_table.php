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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('bachelor_final_mark')->nullable(true);
            $table->integer('master_final_mark')->nullable(true);
            $table->integer('phd_final_mark')->nullable(true);
            $table->boolean('outside_prescribed_time')->nullable(false)->default(false);
            $table->bigInteger('user_id')->nullable(false)->unsigned();
            $table->bigInteger('degree_id')->nullable(false)->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('degree_id')->references('id')->on('degrees');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
