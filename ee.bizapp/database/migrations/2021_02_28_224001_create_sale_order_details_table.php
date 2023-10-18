<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->index();
            $table->string('detail_code');
            $table->integer('product_id');
            $table->string('product_code');
            $table->string('size');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->integer('amount');
            $table->integer('shipping_status')->default(0);//0. Chờ hàng, 1. Đã gửi, 2. Thiếu hàng , 3. Hoàn lại, 4. Mất hàng
            $table->string('shipping_feedback')->nullable();
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
        Schema::dropIfExists('sale_order_details');
    }
}
