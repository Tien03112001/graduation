<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('quantity');
            $table->integer('remain_quantity');
            $table->double('min_order_value', 20, 2);
            $table->integer('min_products_count');
            $table->double('discount_value', 20, 2);
            $table->double('discount_percent', 20, 2);
            $table->boolean('enable')->default(true)->index();
            $table->date('expired_date')->index();
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
        Schema::dropIfExists('vouchers');
    }
}
