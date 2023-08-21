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
            $table->string("name", 100);
            $table->bigInteger('user_id')->unsigned();
            $table->tinyInteger("numOfClassPeriodsPerDay")->unsigned()->default(1);
            $table->json("timeOfEachClassPeriod");
            $table->foreign("user_id")->references('user_id')->on('users');
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
