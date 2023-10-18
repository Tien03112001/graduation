<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModiflyAddColumnUsernameTableAgent extends Migration
{
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->string('user_name')->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('user_name');
        });
    }
}
