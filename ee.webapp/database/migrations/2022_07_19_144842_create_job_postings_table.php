<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->string('image')->nullable();
            $table->string('job_location');
            $table->double('base_salary_min', 20, 2)->nullable();
            $table->double('base_salary_max', 20, 2)->nullable();
            $table->string('unit_currency')->default('VND');
            $table->string('unit_time')->default('month');
            $table->date('date_posted');
            $table->date('valid_through')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('type')->nullable();
            $table->boolean('published')->default(true);
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
        Schema::dropIfExists('job_postings');
    }
}
