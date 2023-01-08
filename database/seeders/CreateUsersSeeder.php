<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin User',
                'address' => 'xy',
                'telephone' => 'xy',
                'email' => 'admin@szolgaltato.com',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ],
            [
                'name' => 'Employer User',
                'address' => 'xy',
                'telephone' => 'xy',
                'email' => 'employer@szolgaltato.com',
                'password' => bcrypt('123456'),
                'role' => 'employer',
            ],
            [
                'name' => 'Jobseeker User',
                'address' => 'xy',
                'telephone' => 'xy',
                'email' => 'jobseeker@szolgaltato.com',
                'password' => bcrypt('123456'),
                'role' => 'jobseeker',
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
