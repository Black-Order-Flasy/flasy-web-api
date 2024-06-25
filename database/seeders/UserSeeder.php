<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@flasy.com',
            'password' => bcrypt('4dm1nflasy'), 
        ]);

        $role = Role::where('name', 'Admin')->first();
        if ($role) {
            $admin->assignRole($role);
        }
    }
}
