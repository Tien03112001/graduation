<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureDataPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structure_data_properties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('type_id')->index();
            $table->string('name');
            $table->string('value_type')->default('text');
            $table->longText('value')->nullable();
            $table->longText('possible_values')->nullable();
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
        Schema::dropIfExists('structure_data_properties');
    }
}
