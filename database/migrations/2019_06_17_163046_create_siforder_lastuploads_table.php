<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiforderLastuploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siforder_lastuploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orders_count')->default(0);
            $table->integer('line_items_count')->default(0);
            $table->float('total_qty', 10, 4)->default(0);
            $table->float('total_sales', 10, 4)->default(0);
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
        Schema::dropIfExists('siforder_lastuploads');
    }
}
