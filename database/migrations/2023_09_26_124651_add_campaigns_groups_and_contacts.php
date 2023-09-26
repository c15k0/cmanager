<?php

use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('campaigns_groups');
        Schema::dropIfExists('campaigns_contacts');
        Schema::create('campaigns_groups', function(Blueprint $table) {
            $table->id();
            $table->integer('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups');
        });
        Schema::create('campaigns_contacts', function(Blueprint $table) {
            $table->id();
            $table->integer('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns_groups');
        Schema::dropIfExists('campaigns_contacts');
    }
};
