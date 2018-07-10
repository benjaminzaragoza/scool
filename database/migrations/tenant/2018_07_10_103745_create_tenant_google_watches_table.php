<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantGoogleWatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_watches', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('channel_id');
            $table->string('token');
            $table->enum('channel_type', ['add', 'delete','makeAdmin','undelete','update']);
            $table->dateTime('expiration_time')->nullable();
            $table->dateTime('expiration_time2')->nullable();
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
        Schema::dropIfExists('google_watches');
    }
}
