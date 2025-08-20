<?php

namespace Tests\Feature;

use App\Models\DivorceCase;
use App\Models\Obligation;
use App\Models\User;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ObligationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role
        Role::create(['name' => 'admin']);

        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);
    }

    public function test_it_can_view_create_obligation_form()
    {
        $divorceCase = DivorceCase::factory()->create();

        $response = $this->get(route('obligations.create', $divorceCase));
        $response->assertOk();
        $response->assertViewHas('divorceCase');
    }

    public function test_it_can_store_obligation()
    {
        $divorceCase = DivorceCase::factory()->create();

        $data = [
            'amount' => 500,
            'start_date' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('obligations.store', $divorceCase), $data);
        $response->assertRedirect(route('divorce-cases.index', $divorceCase));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('obligations', [
            'divorce_case_id' => $divorceCase->id,
            'amount' => 500,
        ]);
    }

    public function test_it_can_view_edit_obligation_form()
    {
        $divorceCase = DivorceCase::factory()->create();
        $obligation = Obligation::factory()->create(['divorce_case_id' => $divorceCase->id]);

        $response = $this->get(route('obligations.edit', [$divorceCase, $obligation]));
        $response->assertOk();
        $response->assertViewHas(['divorceCase', 'obligation']);
    }

    public function test_it_can_update_obligation()
    {
        $divorceCase = DivorceCase::factory()->create();
        $obligation = Obligation::factory()->create(['divorce_case_id' => $divorceCase->id]);

        $data = [
            'amount' => 750,
            'start_date' => now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->patch(route('obligations.update', [$divorceCase, $obligation]), $data);
        $response->assertRedirect(route('divorce-cases.index', $divorceCase));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('obligations', [
            'id' => $obligation->id,
            'amount' => 750,
        ]);
    }
}
