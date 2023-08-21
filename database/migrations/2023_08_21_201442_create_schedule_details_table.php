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
            $table->bigInteger("schedule_id")->unsigned();
            $table->char("subject_id", 10);
            $table->tinyInteger("start");
            $table->tinyInteger("end");
            $table->date("from");
            $table->date("to");
            $table->enum("dateOfWeek", [1,2,3,4,5,6,7])->default(2);
            $table->enum("type", ["ONLINE", "OFFLINE"])->default("OFFLINE");
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
