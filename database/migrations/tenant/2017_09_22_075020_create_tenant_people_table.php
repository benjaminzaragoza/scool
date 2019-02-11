<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantPeopleTable
 */
class CreateTenantPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('identifier_id')->nullable();
            $table->string('givenName')->nullable();
            $table->string('sn1')->nullable();
            $table->string('sn2')->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('birthplace_id')->unsigned()->nullable();
            $table->integer('birthlocation')->nullable();
            $table->enum('gender',['Home','Dona'])->nullable();
            $table->enum('civil_status',['Solter/a','Casat/da','Separat/da','Divorciat/da','Vidu/a'])->nullable();
            $table->string('phone')->nullable();
            $table->json('other_phones')->nullable();
            $table->string('mobile')->nullable();
            $table->json('other_mobiles')->nullable();
            $table->string('email')->nullable();
            $table->json('other_emails')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('person_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->unique(['person_id', 'user_id']);
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_user');
        Schema::dropIfExists('people');
    }
}
