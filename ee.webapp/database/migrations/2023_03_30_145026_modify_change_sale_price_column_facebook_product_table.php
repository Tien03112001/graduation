<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class ModifyChangeSalePriceColumnFacebookProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('facebook_products', function (Blueprint $table) {
            $table->double('sale_price', 20 ,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Type::hasType('double')) {
        Type::addType('double', FloatType::class);
        }
        Schema::table('facebook_products', function (Blueprint $table) {
            $table->double('sale_price', 20 ,2)->change();
        });
    }
}
