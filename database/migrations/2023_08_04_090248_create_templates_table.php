<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Template name');
            $table->string('label')->comment('Template label');
            $table->longText('raw')->comment('Template body');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id', 'fk_template_customer')->on('customers')->references('id');
            $table->unique(['name', 'customer_id'], 'unq_template_customer');
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
        Schema::dropIfExists('templates');
    }
}
