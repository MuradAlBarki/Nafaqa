<?php

namespace Database\Factories;

use App\GenderEnum;
use App\Models\DivorceCase;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChildFactory extends Factory
{
    public function definition(): array
    {
        return [
            'case_id' => DivorceCase::factory(),
            'first_name' => $this->faker->firstName,
            'nationality_no' => $this->faker->unique()->regexify('/[12][0-9]{11}/'),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(GenderEnum::cases()),
            'status' => $this->faker->randomElement(StatusEnum::cases()),
        ];
    }
}