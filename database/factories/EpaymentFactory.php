<?php

namespace Database\Factories;

use App\Models\Epayment;
use App\EpaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class EpaymentFactory extends Factory
{
    protected $model = Epayment::class;

    public function definition(): array
    {
        return [
            'payment_id' => \App\Models\Payment::factory(), // create a payment
            'status' => $this->faker->randomElement(EpaymentStatusEnum::cases())->value,
            'gateway' => $this->faker->randomElement(['PayPal', 'Stripe', 'N-Genius']),
            'response_json' => json_encode([
                'transaction_id' => $this->faker->uuid(),
                'amount' => $this->faker->randomFloat(2, 10, 1000),
                'currency' => 'USD'
            ]),
        ];
    }
}
