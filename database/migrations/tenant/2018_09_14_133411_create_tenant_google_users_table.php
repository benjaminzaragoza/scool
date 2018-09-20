<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantGoogleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('google_id')->unique();;
            $table->string('google_email')->unique();;
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
