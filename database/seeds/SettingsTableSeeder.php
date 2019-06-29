<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Setting::create([
            'site_name' => "Laravel Blog",
            'address' => "malabon",
            'contact_number' => "091088694",
            'contact_email' => "baterna.mark@gmail.com"
        ]);
        
    }
}
