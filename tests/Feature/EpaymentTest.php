<?php

namespace Tests\Feature;

use App\EpaymentStatusEnum;
use App\Models\Epayment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EpaymentTest extends TestCase
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

    public function test_it_can_view_epayments_index()
    {
        $payment = Payment::factory()->create();
        Epayment::factory()->count(3)->create(['payment_id' => $payment->id]);

        $response = $this->get(route('epayments.index', $payment));
        $response->assertOk();
        $response->assertViewHas('epayments');
    }
}
