<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookAdminAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_admin_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_access_token');
            $table->string('long_access_token');
            $table->dateTime('short_expired_at');
            $table->dateTime('long_expired_at');
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
        Schema::dropIfExists('facebook_admin_accounts');
    }
}
