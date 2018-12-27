<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantSubjectGroupsTable.
 */
class CreateTenantSubjectGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('shortname');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('study_id')->unsigned()->nullable();
            $table->unsignedInteger('hours')->nullable();
            $table->unsignedInteger('free_hours')->nullable();
            $table->unsignedInteger('week_hours')->nullable();
            $table->unsignedTinyInteger('number')->nullable();
            $table->unsignedTinyInteger('subjects_number')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
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
        Schema::dropIfExists('subject_groups');
    }
}
