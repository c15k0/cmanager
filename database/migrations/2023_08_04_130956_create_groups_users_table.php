<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id', 'fk_group_contact_group')->on('groups')->references('id');
            $table->unsignedInteger('contact_id');
            $table->foreign('contact_id', 'fk_group_contact_contact')->on('contacts')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups_contacts');
    }
}
