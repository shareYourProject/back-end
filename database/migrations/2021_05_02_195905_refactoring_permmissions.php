<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactoringPermmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('role_user');

        Schema::dropIfExists('roles');

        Schema::table('project_user', function(Blueprint $table) {
            $table->enum('role', config('permission.names'));
        });

        Schema::table('projects', function(Blueprint $table) {
            $table->dropForeign('projects_author_id_foreign');
            $table->dropColumn('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('permissions');
            $table->unsignedBigInteger('project_id');

            $table->foreign('project_id')->references('id')->on('projects');
        });

        Schema::table('project_user', function(Blueprint $table) {
            $table->dropColumn(['role']);
        });

        Schema::table('projects', function(Blueprint $table) {
            $table->unsignedBigInteger('owner_id');

            $table->foreign('owner_id')->references('id')->on('users');
        });
    }
}
