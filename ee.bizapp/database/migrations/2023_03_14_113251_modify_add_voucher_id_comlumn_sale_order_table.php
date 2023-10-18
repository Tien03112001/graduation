<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddVoucherIdComlumnSaleOrderTable extends Migration
{
    public function up()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->string('voucher_id')->nullable()->after('banking_img');
        });
    }

    public function down()
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropColumn('voucher_id');
        });
    }
}
