<?php

namespace Database\Seeders;

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
        $admin = new User();
        $admin->name = 'Admin';
        $admin->username = 'admin';
        $admin->password = Hash::make('123456a@');
        $admin->setRememberToken(Str::random(100));
        $admin->save();
    }
}
