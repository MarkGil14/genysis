<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('system_settings', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('theme_color')->default('#393939');
        //     $table->string('logo')->default('logo/genysis_logo.png');
        //     $table->string('company_name')->default('Company Name');
        //     $table->string('company_address')->default('Company Address');
        //     $table->string('company_telnum')->default('Company Tel Number');
        //     $table->string('company_email')->default('Company@sample.com');
        //     $table->string('database_name')->nullable();
        //     $table->string('database_user')->nullable();
        //     $table->string('database_password')->nullable();
        //     $table->string('database_port')->nullable();

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
        // Schema::dropIfExists('system_settings');
    }
}
