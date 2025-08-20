<?php

namespace Database\Factories;

use App\Models\ProfileRole;
use App\Models\DivorceCase;
use App\GenderEnum;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class DivorceCaseFactory extends Factory
{
    protected $model = DivorceCase::class;

    public function definition(): array
    {
        return [
            'mother_id' => ProfileRole::factory()->state([
                'gender' => GenderEnum::Female->value,
            ]),
            'father_id' => ProfileRole::factory()->state([
                'gender' => GenderEnum::Male->value,
            ]),
            'case_no' => $this->faker->unique()->numerify(),
            'divorce_date' => $this->faker->date(),
            'court_document_url' => $this->faker->url(),
            'status' => $this->faker->randomElement(array_map(fn($e) => $e->value, StatusEnum::cases())),
        ];
    }
}
