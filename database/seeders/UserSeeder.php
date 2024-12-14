<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin', 
            'email' => 'admin@example.com', 
            'password' => bcrypt('password'), 
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Approve1', 
            'email' => 'approve1@example.com', 
            'password' => bcrypt('password'), 
            'role' => 'approve_level_1'
        ]);
        
        User::create([
            'name' => 'Approve2', 
            'email' => 'approve2@example.com', 
            'password' => bcrypt('password'), 
            'role' => 'approve_level_2'
        ]);
    }
}
