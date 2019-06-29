<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiforderreturnLastuploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siforderreturn_lastuploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('returns_count')->default(0);
            $table->integer('line_items_count')->default(0);
            $table->float('total_qty', 10, 4)->default(0);
            $table->float('total_returns', 10, 4)->default(0);
            $table->float('total_discount', 10, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siforderreturn_lastuploads');
    }
}
