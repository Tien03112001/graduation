<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrderShippingPickAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_shipping_pick_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pick_id')->unique();
            $table->string('pick_name');
            $table->string('pick_address');
            $table->string('pick_province');
            $table->string('pick_district');
            $table->string('pick_ward');
            $table->string('pick_street');
            $table->string('pick_tel');
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
        Schema::dropIfExists('sale_order_shipping_pick_addresses');
    }
}
