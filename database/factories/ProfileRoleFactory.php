<?php

namespace Database\Factories;

use App\DocumentTypeEnum;
use App\GenderEnum;
use App\Models\Country;
use App\Models\User;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileRoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nationality_id' => Country::factory(),
            'first_name' => $this->faker->firstName,
            'mid_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date(),
            'national_no' => $this->faker->unique()->numerify('##########'),
            'IBAN' => $this->faker->iban(),
            'document_type' => DocumentTypeEnum::PASSPORT->value,
            'document_no' => $this->faker->unique()->numerify('##########'),
            'document_file_url' => $this->faker->url(),
            'status' => $this->faker->randomElement(StatusEnum::cases()),
            'gender' => $this->faker->randomElement(GenderEnum::cases()),
        ];
    }
}