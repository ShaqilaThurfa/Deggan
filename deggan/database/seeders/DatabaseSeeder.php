<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->updateOrInsert(
            [
                'name' => 'Super Admin',
                'email' => 'superAdmin@mail.com',
                'password' => Hash::make('password'),
                'role' => 'superAdmin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        User::factory()
            ->count(30)
            ->hasNews(5)
            ->create();
    }
}
