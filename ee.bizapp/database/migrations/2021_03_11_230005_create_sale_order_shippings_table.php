<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrderShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_shippings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->index();
            $table->integer('deliver_id')->default(1)->index();
            $table->integer('store_id')->index()->default(0);
            $table->integer('service_id')->index()->default(0);
            $table->string('code')->index();
            $table->string('status');
            $table->integer('status_id')->index();
            $table->integer('total_fee');
            $table->string('expected_delivery_time')->nullable();
            $table->string('note')->nullable();
            $table->boolean('is_printed')->default(false)->index();
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
        Schema::dropIfExists('sale_order_shippings');
    }
}
