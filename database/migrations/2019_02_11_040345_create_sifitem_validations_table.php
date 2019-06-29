<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSifitemValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('sifitem_validations', function (Blueprint $table) {
        //     $table->string('material_code');
        //     $table->string('description');
        //     $table->string('material_group')->nullable();
        //     $table->string('conversion_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('sifitem_validations');
    }
}
