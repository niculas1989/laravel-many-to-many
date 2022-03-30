<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Nicolas';
        $user->email = 'nicolas.maranzano@libero.it';
        $user->password = bcrypt('password');

        $user->save();
    }
}
