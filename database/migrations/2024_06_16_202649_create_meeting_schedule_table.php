<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_schedules', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->string('meeting_title', 255)->nullable();
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->date('date')->nullable();
            $table->string('location', 55)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['upcoming', 'completed', 'canceled'])->default('upcoming');
            $table->string('created_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_schedule');
    }
}
