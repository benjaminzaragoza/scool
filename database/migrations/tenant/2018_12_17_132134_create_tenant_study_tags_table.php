<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantStudyTagsTable.
 */
class CreateTenantStudyTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('tagged_studies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('study_id');
            $table->unsignedInteger('study_tag_id');
            $table->unique(['study_id', 'study_tag_id']);
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
        Schema::dropIfExists('study_tags');
        Schema::dropIfExists('tagged_studies');
    }
}
