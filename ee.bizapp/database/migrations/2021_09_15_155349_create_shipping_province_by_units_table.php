<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProvinceByUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_province_by_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->integer('province_id')->index();
            $table->string('province_name')->index()->nullable();
            $table->integer('partner_id');
            $table->string('partner_code');
            $table->string('partner_value')->nullable();
            $table->string('partner_name');
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
        Schema::dropIfExists('shipping_province_by_units');
    }
}
