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
        App\User::truncate();
    	DB::table('users')->insert([
            'first_name' => 'Leopold',
            'last_name' => 'Bodden',
            'email' => 'lbodden@drivegroupllc.com',
            'password' => Hash::make('123456'),
            'admin' => 1,
            'verified' => 1,
        ]);
    }
}
