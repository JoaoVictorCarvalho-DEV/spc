<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'device_uid' => fake()->uuid() . '-device',
            'location' => fake()->address(),
            'status' => $this->faker->randomElement([
                'online',
                'offline'
            ]),

        ];
    }
}
