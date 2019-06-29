<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSifdspValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('sifdsp_validations', function (Blueprint $table) {
        //     $table->string('account_id')->nullable();
        //     $table->string('first_name')->nullable();
        //     $table->string('last_name')->nullable();
        //     $table->string('account_name')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('sifdsp_validations');
    }
}
