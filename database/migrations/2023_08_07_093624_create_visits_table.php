<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('receiver_id');
            $table->foreign('receiver_id', 'fk_visit_receiver')->on('receivers')->references('id');
            $table->ipAddress();
            $table->mediumText('user_agent');
            $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id', 'fk_visit_customer')->on('customers')->references('id');
            $table->unsignedInteger('campaign_id')->nullable();
            $table->foreign('campaign_id', 'fk_visiti_campaign')->on('campaigns')->references('id');
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
        Schema::dropIfExists('visits');
    }
}
