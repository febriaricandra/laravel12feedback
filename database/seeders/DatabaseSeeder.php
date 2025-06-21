<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Call the UsersSeeder to create initial users
        $this->call(UsersSeeder::class);

        // You can add more seeders here as needed
        // $this->call(OtherSeeder::class);
    }
}
