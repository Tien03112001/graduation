<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyProductsTableAddItemConditionAndBrandAndAvailabilityColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->index()->after('price');
            $table->string('brand')->after('sku');
            $table->string('item_condition')->default('new')->index()->after('brand');
            $table->string('availability')->default('in_stock')->index()->after('item_condition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->dropColumn('brand');
            $table->dropColumn('item_condition');
            $table->dropColumn('availability');
        });
    }
}
