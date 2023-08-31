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
        Schema::create('schedule_details', function (Blueprint $table) {
            $table->id("schedule_detail_id");
            $table->foreignId("schedule_id");
            $table->char("subject_id", 10);
            $table->tinyInteger("start");
            $table->tinyInteger("end");
            $table->date("from");
            $table->date("to");
            $table->json("dateOfWeek");
            $table->enum("type", ["ONLINE", "OFFLINE"])->default("OFFLINE");
            $table->boolean("is_makeUp_class")->default(false);
            $table->foreign("schedule_id")->references('schedule_id')->on('schedules');
            $table->foreign("subject_id")->references('subject_id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_details');
    }
};
