<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->index()->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('facebook_uuid')->nullable()->unique();
            $table->string('zalo_uuid')->nullable()->unique();
            $table->string('telegram_uuid')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->date('dob')->nullable()->unique();
            $table->string('id_number')->nullable()->unique();
            $table->string('locale')->nullable();
            $table->string('gender')->nullable();
            $table->string('timezone')->nullable();
            $table->string('address')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
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
        Schema::dropIfExists('customer_contacts');
    }
}
