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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('sector')->nullable(false);
            $table->date('starting_date')->nullable(false);
            $table->date('ending_date')->nullable(false);
            $table->smallInteger('cfu')->nullable(false)->default(6);
            $table->unsignedInteger('professor')->nullable(false);
            $table->foreign('professor')
                ->references('id')
                ->on('teachers')
                ->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
