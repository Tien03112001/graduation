<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conversation_id')->index();
            $table->string('mid', 512)->unique();
            $table->string('reply_to_mid', 512)->index()->nullable();
            $table->string('quick_reply')->nullable();
            $table->string('sender_name');
            $table->bigInteger('sender_id')->index();
            $table->bigInteger('receiver_id')->index();
            $table->text('content')->nullable();
            $table->string('referral_product_id')->nullable();
            $table->bigInteger('sent_timestamp')->index();
            $table->boolean('sent_status')->default(false)->index();
            $table->bigInteger('read_timestamp')->index()->nullable();
            $table->boolean('read_status')->default(false)->index();
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
        Schema::dropIfExists('facebook_messages');
    }
}
