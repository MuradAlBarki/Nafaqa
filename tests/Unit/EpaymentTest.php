<?php

namespace Tests\Unit;

use App\EpaymentStatusEnum;
use App\Models\Epayment;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EpaymentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'payment_id',
            'gateway',
            'status',
            'response_json',
        ];
        $this->assertEquals($fillable, (new Epayment())->getFillable());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => EpaymentStatusEnum::class,
            'response_json' => 'array',
        ];
        $this->assertEquals($casts, (new Epayment())->getCasts());
    }

    #[Test]
    public function it_belongs_to_payment()
    {
        $payment = Payment::factory()->create();
        $epayment = Epayment::factory()->create(['payment_id' => $payment->id]);

        $this->assertInstanceOf(Payment::class, $epayment->payment);
        $this->assertEquals($payment->id, $epayment->payment->id);
    }

    #[Test]
    public function it_casts_status_at_runtime()
    {
        $epayment = Epayment::factory()->create(['status' => EpaymentStatusEnum::Success]);

        $this->assertInstanceOf(EpaymentStatusEnum::class, $epayment->status);
        $this->assertEquals(EpaymentStatusEnum::Success, $epayment->status);
    }

    #[Test]
    public function it_returns_event_description()
    {
        $epayment = Epayment::factory()->create();
        $description = $epayment->getDescriptionForEvent('created');

        $this->assertEquals("created on Epayment #{$epayment->id}", $description);
    }
}
