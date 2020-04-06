<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$VOunUZVMv4bwC84FJngVdORh/3WOQsLYXcHOTcmsVFWTVWWg.V8/i',
                'remember_token' => null,
            ],
            [
                'id'             => 2,
                'name'           => 'User',
                'email'          => 'user@user.com',
                'password'       => '$2y$10$VOunUZVMv4bwC84FJngVdORh/3WOQsLYXcHOTcmsVFWTVWWg.V8/i',
                'remember_token' => null,
            ],
        ];

        User::insert($users);

    }
}
