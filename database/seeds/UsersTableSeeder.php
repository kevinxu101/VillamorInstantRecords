<?php

use App\User;
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
        DB::table('users')->insert([
            'name'     => "Xu Kevin",
            'email'    => 'kevin.xu@benilde.edu.ph',
            'password' => bcrypt('12345678'),
            'role'     => 'master',
            'active'   => 1,
            'verified' => 1,
        ]);

       
        factory(User::class, 1)->states('teacher')->create();
    
    }
}
