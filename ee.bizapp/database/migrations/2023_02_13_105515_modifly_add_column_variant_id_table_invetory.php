<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModiflyAddColumnVariantIdTableInvetory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_products', function (Blueprint $table) {
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
        Schema::table('inventory_products', function (Blueprint $table) {
            $table->dropColumn('variant_id');
        });
    }
}
