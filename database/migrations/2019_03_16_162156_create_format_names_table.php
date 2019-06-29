<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormatNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('format_names', function (Blueprint $table) {
        //     $table->string('siforder')->default('order');
        //     $table->string('sifdiscount')->default('discount');
        //     $table->string('sifreturn')->default('return');
        //     $table->string('sifitem')->default('item');
        //     $table->string('sifcustomer')->default('customer');
        //     $table->string('sifdsp')->default('dsp');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('format_names');
    }
}
