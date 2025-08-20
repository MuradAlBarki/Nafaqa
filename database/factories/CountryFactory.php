<?php

namespace Database\Factories;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
   public function definition(): array
{
    return [
        'alpha2_code' => $this->faker->unique()->lexify('??'),
        'alpha3_code' => $this->faker->unique()->lexify('???'),
        'english_name' => $this->faker->word(),
        'arabic_name' => $this->faker->word(),
        'phone_code' => '+' . $this->faker->numberBetween(1, 999),
        'status' => $this->faker->randomElement([1, 2, 3]),
    ];
}

}