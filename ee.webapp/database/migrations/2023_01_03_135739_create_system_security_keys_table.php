<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSecurityKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_security_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('algorithm')->default('aes-256-cbc');
            $table->string('iv', 16);
            $table->string('encryptionKey', 100);
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
        Schema::dropIfExists('system_security_keys');
    }
}
