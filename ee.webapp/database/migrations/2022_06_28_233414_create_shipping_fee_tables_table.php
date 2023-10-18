<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingFeeTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_fee_tables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('province_id')->index();
            $table->bigInteger('district_id')->index();
            $table->bigInteger('ward_id');
            $table->double('fee', 20, 2);
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
        Schema::dropIfExists('shipping_fee_tables');
    }
}
