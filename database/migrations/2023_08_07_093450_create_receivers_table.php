<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('receivers');
        Schema::create('receivers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campaign_id');
            $table->foreign('campaign_id', 'fk_receiver_campaign')->on('campaigns')->references('id');
            $table->unsignedInteger('contact_id');
            $table->foreign('contact_id', 'fk_receiver_contact')->on('contacts')->references('id');
            $table->string('hash')->unique();
            $table->enum('status', ['created', 'sent', 'error'])->default('created');
            $table->text('error')->nullable();
            $table->timestamp('first_opened_at')->nullable(true);
            $table->timestamp('last_opened_at')->nullable(true);
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
        Schema::dropIfExists('receivers');
    }
}
