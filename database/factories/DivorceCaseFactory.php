<?php

namespace Database\Factories;

use App\Models\ProfileRole;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class DivorceCaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mother_id' => ProfileRole::factory(),
            'father_id' => ProfileRole::factory(),
            'case_no' => $this->faker->unique()->numerify('CASE-####'),
            'divorce_date' => $this->faker->date(),
            'court_document' => $this->faker->word.'.pdf',
            'status' => $this->faker->randomElement(StatusEnum::cases()),
        ];
    }
}