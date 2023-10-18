<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('priority')->index();
            $table->unsignedDouble('min_amount_spent')->index();
            $table->unsignedDouble('min_purchases_count')->index();
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
        Schema::dropIfExists('customer_ranks');
    }
}
