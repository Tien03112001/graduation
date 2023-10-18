<?php

namespace Database\Seeders;

use App\Models\SystemUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = SystemUser::query()->count('id');
        if ($count == 0) {
            SystemUser::query()->create([
                'name' => 'Ezi Solutions',
                'username' => 'ezi',
                'password' => Hash::make('ezisolutions.vn'),
                'remember_token' => Str::random(100)
            ]);
        }
    }
}
