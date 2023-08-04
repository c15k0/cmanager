<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Contact name');
            $table->string('last_name')->comment('Contact last name')->nullable();
            $table->string('company_name')->comment('Company name');
            $table->string('email')->comment('Email contact');
            $table->string('phone')->comment('Optional phone')->nullable();
            $table->timestamp('bounced_at')->comment('Date when contact bounced the email')->nullable();
            $table->timestamp('unsubscribed_at')->comment('Date when contact has been unsuscribed from customer')->nullable();
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id', 'fk_contact_customer')->on('customers')->references('id');
            $table->unique(['email', 'customer_id'], 'unq_contact_customer');
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
        Schema::dropIfExists('contacts');
    }
}
