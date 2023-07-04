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
        Schema::create('subjects', function (Blueprint $table) {
            $table->char("subject_id", 10)->primary();
            $table->string("name", 100);
            $table->tinyInteger("credits");
            $table->bigInteger("teacher_id")->unsigned();
            $table->foreign("teacher_id")->references('teacher_id')->on('teachers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
