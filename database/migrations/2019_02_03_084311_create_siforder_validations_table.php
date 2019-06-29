<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiforderValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('siforder_validations', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('sales_type');
        //     $table->string('account_reference_id');
        //     $table->string('sasid');
        //     $table->string('dspid');
        //     $table->string('sales_order_number');
        //     $table->string('order_date');
        //     $table->string('order_total');
        //     $table->string('order_total_discount');
        //     $table->string('requested_delivery_date');
        //     $table->string('payment_term');
        //     $table->string('invoice_number');
        //     $table->string('order_discount_type');
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
        // Schema::dropIfExists('siforder_validations');
    }
}
