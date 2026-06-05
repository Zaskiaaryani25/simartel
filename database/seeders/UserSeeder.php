<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
             ['name' => 'UID', 'username' => 'UID', 'password' => 'UID123'],
            ['name' => 'UP3 Tanjung Karang', 'username' => 'up3_tanjungkarang', 'password' => 'tanjungkarang123'],
            ['name' => 'UP3 Metro',          'username' => 'up3_metro',          'password' => 'metro123'],
            ['name' => 'UP3 Kota Bumi',      'username' => 'up3_kotabumi',       'password' => 'kotabumi123'],
            ['name' => 'UP3 Pringsewu',      'username' => 'up3_pringsewu',      'password' => 'pringsewu123'],
        ];

        foreach ($units as $u) {
            User::create([
                'name'     => $u['name'],
                'username' => $u['username'],
                'password' => Hash::make($u['password']),
            ]);
        }
    }
}