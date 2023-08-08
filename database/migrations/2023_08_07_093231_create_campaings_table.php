<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique()->comment('Campaign code');
            $table->string('title')->comment('Campaign name');
            $table->timestamp('start_at')->nullable();
            $table->unsignedInteger('template_id');
            $table->foreign('template_id', 'fk_campaign_template')->on('templates')->references('id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id', 'fk_campaign_customer')->on('customers')->references('id');
            $table->enum('status', ['created', 'ready', 'sending', 'sent', 'error', 'cancelled'])->default('created');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
