<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('description', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->string('robots', 255)->nullable();
            $table->string('canonical', 255)->nullable();
            $table->text('more')->nullable();
            $table->bigInteger('metaable_id');
            $table->string('metaable_type');
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
        Schema::dropIfExists('meta_data');
    }
}
