<?php

namespace Database\Factories;

use App\Models\DivorceCase;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObligationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'divorce_case_id' => DivorceCase::factory(),
            'amount' => $this->faker->randomFloat(2, 200, 1000),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'status' =>  $this->faker->randomElement(StatusEnum::cases()),
        ];
    }
}
