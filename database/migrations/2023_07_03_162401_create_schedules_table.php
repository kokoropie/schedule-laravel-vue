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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id("schedule_id");
            $table->char("subject_id", 10);
            $table->tinyInteger("start");
            $table->tinyInteger("end");
            $table->date("from");
            $table->date("to");
            $table->enum("dateOfWeek", [1,2,3,4,5,6,7])->default(2);
            $table->enum("type", ["ONLINE", "OFFLINE"])->default("OFFLINE");
            $table->foreign("subject_id")->references('subject_id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
