<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('users')->insert([
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@softui.com',
                'password' => Hash::make('secret'),
                'created_at' => now(),
                'updated_at' => now()
        ]);

        DB::table('users')->insert([
                'id' => 2,
                'name' => 'Umair',
                'email' => 'umairakram@gmail.com',
                'password' => Hash::make('secret'),
                'campaigns' => '2',
                'created_at' => now(),
                'updated_at' => now()
        ]);


        DB::table('users')->insert([
                'id' => 3,
                'name' => 'Usman',
                'email' => 'usmanakram@gmail.com',
                'password' => Hash::make('secret'),
                'campaigns' => '1',
                'created_at' => now(),
                'updated_at' => now()
        ]);

    }
}
