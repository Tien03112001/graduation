<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->index();
            $table->boolean('enable')->default(true)->index();
            $table->string('gid');
            $table->string('title');
            $table->text('description');
            $table->string('link');
            $table->string('image_link');
            $table->string('additional_image_link');
            $table->string('availability');
            $table->string('availability_​​date');
            $table->double('price', 20, 2);
            $table->double('sale_price', 20, 2);
            $table->string('google_product_category');
            $table->string('product_type');
            $table->string('brand');
            $table->string('gtin');
            $table->string('mpn');
            $table->string('condition');
            $table->string('adult');
            $table->string('age_group');
            $table->string('color');
            $table->string('gender');
            $table->string('material');
            $table->string('pattern');
            $table->string('size');
            $table->string('size_type');
            $table->string('item_group_id');
            $table->string('custom_label_0');
            $table->string('custom_label_1');
            $table->string('custom_label_2');
            $table->string('custom_label_3');
            $table->string('custom_label_4');
            $table->string('promotion_id');
            $table->string('currency');
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
        Schema::dropIfExists('g_products');
    }
}
