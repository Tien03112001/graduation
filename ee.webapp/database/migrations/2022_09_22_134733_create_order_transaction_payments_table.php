<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->index();
            $table->string('code');
            $table->string('payer_name');
            $table->string('payer_email')->nullable();
            $table->string('payer_phone')->nullable();
            $table->double('amount', 20, 2);
            $table->string('currency');
            $table->string('method');
            $table->string('status');
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
        Schema::dropIfExists('order_transaction_payments');
    }
}
