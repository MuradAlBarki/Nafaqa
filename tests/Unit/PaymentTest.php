<?php

namespace Tests\Unit;

use App\EpaymentStatusEnum;
use App\Models\DivorceCase;
use App\Models\Epayment;
use App\Models\Payment;
use App\PaymentStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'divorce_case_id',
            'obligation_id',
            'amount',
            'payment_date',
            'due_date',
            'proof_document_url',
            'status',
        ];
        $this->assertEquals($fillable, (new Payment())->getFillable());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => PaymentStatusEnum::class,
            'payment_date' => 'date',
            'due_date' => 'date',
        ];
        $this->assertEquals($casts, (new Payment())->getCasts());
    }

    #[Test]
    public function it_belongs_to_a_divorce_case()
    {
        $case = DivorceCase::factory()->create();
        $payment = Payment::factory()->create(['divorce_case_id' => $case->id]);

        $this->assertInstanceOf(DivorceCase::class, $payment->divorceCase);
        $this->assertEquals($case->id, $payment->divorceCase->id);
    }

    #[Test]
    public function it_has_many_epayments()
    {
        $payment = Payment::factory()->create();
        $epayments = Epayment::factory()->count(3)->create(['payment_id' => $payment->id]);

        $this->assertTrue($payment->epayments->contains($epayments->first()));
        $this->assertCount(3, $payment->epayments);
    }

    #[Test]
    public function it_returns_latest_successful_epayment()
    {
        $payment = Payment::factory()->create();
        $failed = Epayment::factory()->create([
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Failed
        ]);
        $success = Epayment::factory()->create([
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Success
        ]);

        $this->assertEquals($success->id, $payment->epayment()->id);
    }

    #[Test]
    public function it_checks_if_epaid()
    {
        $payment = Payment::factory()->create();
        Epayment::factory()->create([
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Success
        ]);

        $this->assertTrue($payment->epaid);
    }

    #[Test]
    public function it_casts_status_at_runtime()
    {
        $payment = Payment::factory()->create(['status' => PaymentStatusEnum::PaidNotVerified]);

        $this->assertInstanceOf(PaymentStatusEnum::class, $payment->status);
        $this->assertEquals(PaymentStatusEnum::PaidNotVerified, $payment->status);
    }

    #[Test]
    public function it_returns_event_description()
    {
        $payment = Payment::factory()->create();
        $description = $payment->getDescriptionForEvent('created');

        $this->assertEquals("created on Payment #{$payment->id}", $description);
    }
}
