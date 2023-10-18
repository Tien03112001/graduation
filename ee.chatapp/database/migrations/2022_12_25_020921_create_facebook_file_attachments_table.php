<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookFileAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_file_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('collection_id')->default(0)->index();
            $table->bigInteger('page_id')->index();
            $table->string('type');
            $table->text('url');
            $table->string('title')->nullable();
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
        Schema::dropIfExists('facebook_file_attachments');
    }
}
