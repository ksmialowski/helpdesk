<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleIds = DB::table('roles')
            ->whereIn('name', ['admin', 'agent', 'reporter'])
            ->pluck('id', 'name');

        DB::table('users')->insert([
            [
                'name'              => 'Administrator',
                'email'             => 'admin@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
                'role_id'           => $roleIds['admin'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Jan Agent',
                'email'             => 'agent@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
                'role_id'           => $roleIds['agent'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Anna Reporter',
                'email'             => 'reporter@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
                'role_id'           => $roleIds['reporter'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
