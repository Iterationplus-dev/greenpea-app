<?php

namespace Database\Factories;

use App\Enums\Cities;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory()->create(['role' => UserRole::PROPERTY_OWNER])->id,
            'name' => fake()->company,
            'slug' => fake()->slug,
            'description' => fake()->paragraph,
            'is_active' => fake()->boolean,
            'city' => fake()->randomElement(array_column(Cities::cases(), 'value')),
            'address' => fake()->address,
        ];
    }
}
