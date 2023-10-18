<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->index();
            $table->string('category_slug');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('summary')->nullable();
            $table->double('sale_price', 20, 2);
            $table->double('price', 20, 2);
            $table->bigInteger('order')->index();
            $table->boolean('published')->default(true)->index();
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
        Schema::dropIfExists('products');
    }
}
