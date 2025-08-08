<?php

namespace Database\Factories;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    public function definition(): array
    {
        $country = $this->faker->unique()->country();
        
        return [
            'alpha2_code' => strtoupper($this->faker->unique()->lexify('??')),
            'alpha3_code' => strtoupper($this->faker->unique()->lexify('???')),
            'english_name' => $country,
            'arabic_name' => $this->faker->optional()->word(),
            'phone_code' => $this->faker->optional()->numerify('+###'),
            'status' => $this->faker->randomElement(StatusEnum::cases()),
        ];
    }
}