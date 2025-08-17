<?php
namespace Database\Factories;

use App\Models\DivorceCase;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'divorce_case_id' => DivorceCase::factory(),
            'monthly_amount' => $this->faker->randomFloat(2, 200, 1000),
            'due_date' => $this->faker->date(),
            'payment_date' => $this->faker->date(),
            'proof_document_url' => $this->faker->url(),
            'status' =>  $this->faker->randomElement(StatusEnum::cases()),
        ];
    }
}
