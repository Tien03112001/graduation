<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyChangColumnFacebookProductTable extends Migration
{
    public function up()
    {
        Schema::table('facebook_products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('facebook_products', function (Blueprint $table) {
            $table->text('description')->change();
        });
    }
}
