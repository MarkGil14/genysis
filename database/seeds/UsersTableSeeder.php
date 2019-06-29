<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::create([

            'name' => 'mark baterna',
            'email' => 'baterna.mark@gmail.com',
            'password' => bcrypt('baterna'),
            'admin' => 1

        ]);

        App\Profile::create([
            'user_id' => $user->id,
            'avatar' => 'link image',
            'about' => 'sample about',
            'facebook' => 'facebook.com',
            'youtube' => 'youtube.com'
        ]);


    }
}
