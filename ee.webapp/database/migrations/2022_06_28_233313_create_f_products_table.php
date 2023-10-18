<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->index();
            $table->boolean('enable')->default(true)->index();
            $table->string('fid');
            $table->string('title');
            $table->text('description');
            $table->string('availability');
            $table->string('condition');
            $table->double('price', 20, 2);
            $table->string('link');
            $table->string('image_link');
            $table->string('brand');
            $table->integer('quantity_to_sell_on_facebook');
            $table->string('fb_product_category');
            $table->string('google_product_category');
            $table->string('size');
            $table->double('sale_price', 20, 2);
            $table->string('sale_price_effective_date')->nullable();
            $table->string('item_group_id');
            $table->string('status');
            $table->string('additional_image_link');
            $table->string('color');
            $table->string('gender');
            $table->string('material');
            $table->string('pattern');
            $table->string('custom_label_0');
            $table->string('custom_label_1');
            $table->string('custom_label_2');
            $table->string('custom_label_3');
            $table->string('custom_label_4');
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
        Schema::dropIfExists('f_products');
    }
}
