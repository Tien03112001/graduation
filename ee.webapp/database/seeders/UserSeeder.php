<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = Role::query()->count('id');
        if ($count == 0) {
            $roleAdmin = User::query()->create([
                'name' => 'Admin',
                'description' => 'Quản trị viên'
            ]);
            $admin = User::query()->create([
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('123456a@'),
                'remember_token' => Str::random(100)
            ]);
            $admin->roles()->attach($roleAdmin->id);
        }
    }
}
