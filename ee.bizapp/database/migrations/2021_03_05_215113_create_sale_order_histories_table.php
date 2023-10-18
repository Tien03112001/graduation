<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index();
            $table->integer('order_id')->index();
            $table->string('from_status')->index();
            $table->string('to_status')->index();
            $table->text('note')->nullable();
            $table->date('date')->index();
            $table->integer('day_of_week')->index();
            $table->integer('day_of_month')->index();
            $table->integer('month')->index();
            $table->integer('year')->index();
            $table->integer('hour')->index();
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
        Schema::dropIfExists('sale_order_histories');
    }
}
