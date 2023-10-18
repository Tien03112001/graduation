<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conversation_id')->index();
            $table->bigInteger('session_id')->index();
            $table->bigInteger('agent_id')->index();
            $table->string('sender_name');
            $table->tinyInteger('type')->index();
            $table->text('content')->nullable();
            $table->nullableMorphs('source');
            $table->boolean('sent_status')->default(false)->index();
            $table->boolean('opened_status')->default(false)->index();
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
        Schema::dropIfExists('messages');
    }
}
