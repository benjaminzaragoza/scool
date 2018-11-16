<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantLogsTable
 */
class CreateTenantLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->datetime('time');
            $table->string('action_type');
            $table->string('module_type');
            $table->unsignedInteger('user_id')->nullable();
            $table->nullableMorphs('loggable');
            $table->json('persistedLoggable')->nullable();
            $table->string('icon');
            $table->string('color');
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
        Schema::dropIfExists('logs');
    }
}
