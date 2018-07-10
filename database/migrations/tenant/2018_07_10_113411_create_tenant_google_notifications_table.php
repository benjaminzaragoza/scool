<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantGoogleNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('channel_id');
            $table->enum('channel_type', ['sync','add', 'delete','makeAdmin','undelete','update']);
            $table->string('token')->nullable();
            $table->unsignedInteger('message_number');
            $table->dateTime('expiration_time')->nullable();
            $table->boolean('valid')->default(false);
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
        Schema::dropIfExists('google_notifications');
    }
}
