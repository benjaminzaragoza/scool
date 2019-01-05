<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantPositionsTable
 */
class CreateTenantPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->boolean('deletable')->default(true);
            $table->timestamps();
        });

        Schema::create('position_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('position_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
