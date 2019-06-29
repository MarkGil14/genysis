<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSifdiscountHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('sifdiscount_headers', function (Blueprint $table) {
        //     $table->string('invoice_number');
        //     $table->string('discount_description');
        //     $table->string('discount_amount');
        //     $table->string('material_code');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('sifdiscount_headers');
    }
}
