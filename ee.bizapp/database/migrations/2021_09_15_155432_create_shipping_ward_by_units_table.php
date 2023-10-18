<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingWardByUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_ward_by_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->integer('ward_id')->index();
            $table->string('ward_name')->index()->nullable();
            $table->integer('district_id')->index();
            $table->string('district_name')->index()->nullable();
            $table->integer('province_id')->index();
            $table->string('province_name')->index()->nullable();
            $table->integer('partner_id');
            $table->integer('partner_district_id');
            $table->integer('partner_province_id');
            $table->string('partner_code')->nullable();
            $table->string('partner_value')->nullable();
            $table->string('partner_name');
            $table->string('partner_district_name');
            $table->string('partner_province_name');
            $table->float('rate')->default(0);
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
        Schema::dropIfExists('shipping_ward_by_units');
    }
}
