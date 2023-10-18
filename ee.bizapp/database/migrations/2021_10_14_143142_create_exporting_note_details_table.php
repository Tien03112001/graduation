<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportingNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exporting_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('note_id')->index();
            $table->integer('inventory_product_id')->index()->nullable();
            $table->integer('product_id')->index()->nullable();
            $table->string('product_code')->nullable();
            $table->string('size')->nullable();
            $table->integer('quantity')->default(1);
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
        Schema::dropIfExists('exporting_note_details');
    }
}
