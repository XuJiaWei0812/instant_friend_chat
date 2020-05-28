<?php

use Illuminate\Database\Seeder;
use App\user;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1;$i<=5;$i++) {
            $data=[
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('srca555'),
            ];

          User::create($data);
        }
    }
}
