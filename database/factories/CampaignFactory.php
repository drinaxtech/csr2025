<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // database/factories/CampaignFactory.php
public function definition(): array
{
    return [
        'title' => implode(' ', $this->faker->words(5)), // This is correct
        // ... other fields
        'description' => $this->faker->paragraph, // This is also correct
        // If you had something like:
        // 'some_field' => $this->faker->a_typoed_format(), // This would cause an error
    ];
}
}
