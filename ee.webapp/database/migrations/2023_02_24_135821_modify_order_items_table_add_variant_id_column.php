<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOrderItemsTableAddVariantIdColumn extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('variant_id')->index()->after('product_name');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('variant_id');
        });
    }
}
