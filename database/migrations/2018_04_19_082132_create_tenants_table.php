<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('subdomain')->unique();
            $table->string('email_domain');
            $table->string('hostname');
            $table->string('username');
            $table->string('password');
            $table->string('database');
            $table->integer('port');
            $table->string('gsuite_service_account_path')->nullable();
            $table->string('gsuite_admin_email')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
