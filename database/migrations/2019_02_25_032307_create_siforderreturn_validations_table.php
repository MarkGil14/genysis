<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiforderreturnValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('siforderreturn_validations', function (Blueprint $table) {
        //     $table->string('return_date');
        //     $table->string('invoice_number');
        //     $table->string('account_id');
        //     $table->string('sas_id');
        //     $table->string('dsp_id');
        //     $table->string('material_code');
        //     $table->string('returned_quantity');
        //     $table->string('price');
        //     $table->string('discount_amount');
        //     $table->string('type_of_return');
        //     $table->string('condition');
        //     $table->string('return_type');
        //     $table->string('credit_memo_number');
        //     $table->string('reason_of_return');
        //     $table->string('reason_of_rejection');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('siforderreturn_validations');
    }
}
