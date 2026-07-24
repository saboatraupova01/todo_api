<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'category_id' => Category::inRandomOrder()->value('id'),

            'title' => fake()->sentence(),

            'description' => fake()->paragraph(),

            'status' => fake()->randomElement([
                TaskStatus::NEW,
                TaskStatus::IN_PROGRESS,
                TaskStatus::DONE,
            ]),
        ];
    }
}
