<?php

namespace Tests\Feature;

use App\EpaymentStatusEnum;
use App\PaymentStatusEnum;
use App\Models\DivorceCase;
use App\Models\Epayment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);
    }

    public function test_it_can_view_create_payment_form()
    {
        $divorceCase = DivorceCase::factory()->create();

        $response = $this->get(route('payments.create', $divorceCase));
        $response->assertOk();
        $response->assertViewHas('divorceCase');
    }

    public function test_it_can_store_payment()
    {
        $divorceCase = DivorceCase::factory()->create();

        $data = [
            'amount' => 1000,
            'due_date' => now()->addDays(1)->format('Y-m-d'),
        ];

        $response = $this->post(route('divorce-cases.payments.store', $divorceCase), $data);
        $response->assertRedirect(route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'divorce_case_id' => $divorceCase->id,
            'amount' => 1000,
        ]);
    }

    public function test_it_can_view_edit_payment_form()
    {
        $divorceCase = DivorceCase::factory()->create();
        $payment = Payment::factory()->create(['divorce_case_id' => $divorceCase->id]);

        $response = $this->get(route('payments.edit', [$divorceCase, $payment]));
        $response->assertOk();
        $response->assertViewHas(['divorceCase', 'payment', 'status']);
    }

    public function test_it_can_update_payment()
    {
        $divorceCase = DivorceCase::factory()->create();
        $payment = Payment::factory()->create(['divorce_case_id' => $divorceCase->id]);

        $data = [
            'amount' => 1200,
            'due_date' => now()->addDays(2)->format('Y-m-d'),
            'status' => PaymentStatusEnum::Entry->value,
        ];

        $response = $this->patch(route('payments.update', [$divorceCase, $payment]), $data);
        $response->assertRedirect(route('divorce-cases.obligations.show', [$divorceCase, $divorceCase->obligation]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'amount' => 1200,
        ]);
    }

    public function test_it_can_mark_payment_as_paid()
    {
        Storage::fake('public');
        $divorceCase = DivorceCase::factory()->create();
        $payment = Payment::factory()->create([
            'divorce_case_id' => $divorceCase->id,
            'status' => PaymentStatusEnum::Entry->value,
        ]);

        $file = UploadedFile::fake()->create('proof.pdf', 100);

        $response = $this->patch(route('payments.pay', [$divorceCase, $payment]), [
            'proof_document' => $file
        ]);

        $response->assertRedirect(route('divorce-cases.payments.index', [$divorceCase, $payment]));
        $response->assertSessionHas('success');

        $this->assertEquals(PaymentStatusEnum::PaidNotVerified->value, $payment->fresh()->status->value);
        Storage::disk('public')->assertExists($payment->fresh()->proof_document_url);
    }

    public function test_it_can_create_epayment_success()
    {
        $divorceCase = DivorceCase::factory()->create();
        $payment = Payment::factory()->create(['divorce_case_id' => $divorceCase->id]);

        $response = $this->post(route('payments.success', $payment), [
            'gateway' => 'TestGateway',
            'response' => ['transaction' => '123']
        ]);

        $this->assertDatabaseHas('epayments', [
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Success->value,
        ]);

        $this->assertEquals(PaymentStatusEnum::PaidNotVerified->value, $payment->fresh()->status->value);
    }

    public function test_it_can_create_epayment_fail()
    {
        $divorceCase = DivorceCase::factory()->create();
        $payment = Payment::factory()->create(['divorce_case_id' => $divorceCase->id]);

        $response = $this->post(route('payments.fail', $payment), [
            'gateway' => 'TestGateway',
            'response' => ['transaction' => '123']
        ]);

        $this->assertDatabaseHas('epayments', [
            'payment_id' => $payment->id,
            'status' => EpaymentStatusEnum::Failed->value,
        ]);
    }
}
