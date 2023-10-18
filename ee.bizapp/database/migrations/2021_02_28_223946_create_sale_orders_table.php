<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id')->index();
            $table->string('code')->unique();
            $table->string('channel');

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('customer_request')->nullable();

            $table->integer('amount');
            $table->integer('ship_fee');
            $table->integer('total_amount');
            $table->integer('cod_fee')->default(0);

            $table->integer('payment_type');//0. CoD, 1. Pay all, 2. Đặt cọc, 3. Trả nợ
            $table->integer('pre_charged')->default(0);
            $table->string('banking_sms')->nullable();

            $table->string('status')->default('Chờ')->index();

            $table->boolean('is_completed')->default(false)->index();

            $table->text('note')->nullable();

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
        Schema::dropIfExists('sale_orders');
    }
}
