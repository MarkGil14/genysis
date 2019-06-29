<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiforderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('siforder_headers', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('sales_type');
        //     $table->string('invoice_number');
        //     $table->string('material_code');
        //     $table->string('date');
        //     $table->string('delivery_date');
        //     $table->string('account_id');
        //     $table->string('quantity');
        //     $table->string('price');
        //     $table->string('dsp_id');
        //     $table->string('sas_id');
        //     $table->string('payment_term');
        //     $table->string('transaction_id');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('siforder_headers');
    }
}
