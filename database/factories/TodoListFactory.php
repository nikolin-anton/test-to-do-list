<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TodoList>
 */
class TodoListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = [
            null,
            'todo',
            'done',
        ];
        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->text,
            'status' => $status[rand(0,2)],
            'priority' => rand(1,5)
        ];
    }
}
