<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
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
            $table->integer('quantity_to_sell_on_facebook')->nullable();;
            $table->string('fb_product_category')->nullable();
            $table->string('size')->nullable();
            $table->double('sale_price', 20, 2);
            $table->date('sale_price_effective_date')->nullable();
            $table->string('item_group_id')->nullable();
            $table->string('status');
            $table->text('additional_image_link')->nullable();
            $table->longText('rich_text_description')->nullable();
            $table->string('color')->nullable();
            $table->string('gender')->nullable();
            $table->string('age_group')->nullable();
            $table->string('material')->nullable();
            $table->string('pattern')->nullable();
            $table->string('height')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('finish')->nullable();
            $table->string('volume')->nullable();
            $table->longText('scent')->nullable();
            $table->longText('decor_style')->nullable();
            $table->longText('gemstone')->nullable();
            $table->longText('ingredients')->nullable();
            $table->string('custom_label_0')->nullable();
            $table->string('custom_label_1')->nullable();
            $table->string('custom_label_2')->nullable();
            $table->string('custom_label_3')->nullable();
            $table->string('custom_label_4')->nullable();
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
        Schema::dropIfExists('facebook_products');
    }
}
