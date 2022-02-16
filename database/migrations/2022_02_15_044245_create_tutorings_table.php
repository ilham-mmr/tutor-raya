<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tutor_id')->unsigned();
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->double('hourly_price');
            $table->string('status')->default('AVAILABLE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutorings');
    }
}
