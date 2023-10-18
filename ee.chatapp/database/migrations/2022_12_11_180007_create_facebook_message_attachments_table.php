<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookMessageAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('message_id')->index();
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('title')->nullable();
            $table->string('sticker_id')->nullable();
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
        Schema::dropIfExists('facebook_message_attachments');
    }
}
