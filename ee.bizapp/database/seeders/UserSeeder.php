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
        $user = new User();
        $user->name = 'Quản trị viên';
        $user->username = 'admin';
        $user->password = Hash::make('123456a@');
        $user->remember_token = Str::random(100);
        $user->save();
    }
}
