<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantIncidentTagsTable.
 */
class CreateTenantIncidentTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('tagged_incidents', function (Blueprint $table) {
            $table->unsignedInteger('incident_id');
            $table->unsignedInteger('incident_tag_id');
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
        Schema::dropIfExists('incident_tags');
        Schema::dropIfExists('tagged_incidents');
    }
}
