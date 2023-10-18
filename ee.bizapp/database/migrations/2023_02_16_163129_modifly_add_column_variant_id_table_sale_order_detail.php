<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModiflyAddColumnVariantIdTableSaleOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_order_details', function (Blueprint $table) {
            $table->bigInteger('variant_id')->index()->after('product_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_order_details', function (Blueprint $table) {
            $table->dropColumn('variant_id');
        });
    }
}
