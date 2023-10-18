<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddSomeColumnProductVariantTable extends Migration
{
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->double('weight', 20, 2)->index()->after('product_id');
            $table->double('length', 20, 2)->index()->after('weight');
            $table->double('width', 20, 2)->index()->after('length');
            $table->double('height', 20, 2)->index()->after('width');
        });
    }

    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
