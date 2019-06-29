<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSifcustomerHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('sifcustomer_headers', function (Blueprint $table) {
        //     $table->string('account_id');
        //     $table->string('account_name');
        //     $table->string('channel');
        //     $table->string('street');
        //     $table->string('city');            
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('sifcustomer_headers');
    }
}
