<?php

namespace Database\Seeders;

use App\Models\SystemSecurityKey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SystemSecurityKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = SystemSecurityKey::query()->count('id');
        if ($count == 0) {
            SystemSecurityKey::query()->create([
                'name' => 'Khóa hệ thống',
                'algorithm' => 'aes-256-cbc',
                'iv' => Str::random(16),
                'encryptionKey' => Str::random(100)
            ]);
        }
    }
}
