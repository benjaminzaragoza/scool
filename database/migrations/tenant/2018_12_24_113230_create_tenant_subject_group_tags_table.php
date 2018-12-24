<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantIncidentTagsTable.
 */
class CreateTenantSubjectGroupTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_group_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('tagged_subject_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subject_group_id');
            $table->unsignedInteger('subject_group_tag_id');
            $table->unique(['subject_group_id', 'subject_group_tag_id']);
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
        Schema::dropIfExists('subject_group_tags');
        Schema::dropIfExists('tagged_subject_groups');
    }
}
