<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingFeeConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_fee_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('min_products_count')->default(0);
            $table->integer('min_order_value')->default(0);
            $table->boolean('enable')->default(true);
            $table->integer('fee')->default(0);
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
        Schema::dropIfExists('shipping_fee_conditions');
    }
}
