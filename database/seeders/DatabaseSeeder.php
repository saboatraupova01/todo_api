<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(500)->create();

        $userIds = $users->pluck('id')->toArray();
        Message::factory(50000)->create([
            'user_id' => fn () => fake()->randomElement($userIds),
        ]);
    }
}
