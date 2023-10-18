<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingDistrictByUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_district_by_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->integer('district_id')->index();
            $table->string('district_name')->index()->nullable();
            $table->integer('province_id')->index();
            $table->string('province_name')->index()->nullable();
            $table->integer('partner_id');
            $table->string('partner_code');
            $table->integer('partner_province_id');
            $table->string('partner_value')->nullable();
            $table->string('partner_name');
            $table->string('partner_province_name');
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
        Schema::dropIfExists('shipping_district_by_units');
    }
}
