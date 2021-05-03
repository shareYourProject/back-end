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

        Schema::table('roles', function(Blueprint $table) {
            $table->dropForeign('roles_project_id_foreign');

            $table->dropColumn(['permissions', 'project_id']);
        });

        Schema::table('project_user', function(Blueprint $table) {
            $table->unsignedBigInteger('role_id');

            $table->foreign('role_id')->references('id')->on('roles');
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

        Schema::table('roles', function (Blueprint $table) {
            $table->json('permissions');
            $table->unsignedBigInteger('project_id');

            $table->foreign('project_id')->references('id')->on('projects');
        });

        Schema::table('project_user', function(Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
    }
}
