<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSifcustomerValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('sifcustomer_validations', function (Blueprint $table) {
        //     $table->string('account_id');
        //     $table->string('account_name');
        //     $table->string('channel')->nullable();
        //     $table->string('street')->nullable();
        //     $table->string('city')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('sifcustomer_validations');
    }
}
