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
            $table->char("subject_id", 10);
            $table->string("name", 100);
            $table->tinyInteger("credits");
            $table->char('color_background')->default('#ffffff');
            $table->char('color_foreground')->default('#000000');
            $table->foreignId("teacher_id");
            $table->foreignId('user_id');
            $table->unique(['subject_id', 'user_id']);
            $table->foreign("user_id")->references('user_id')->on('users');
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
