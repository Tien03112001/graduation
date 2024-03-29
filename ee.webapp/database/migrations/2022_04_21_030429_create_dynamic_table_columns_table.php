<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicTableColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_table_columns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('table_id')->index();
            $table->string('name', 255);
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->unique(['table_id', 'name']);
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
        Schema::dropIfExists('dynamic_table_columns');
    }
}
