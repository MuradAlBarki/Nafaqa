<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogTest extends TestCase
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

    public function test_it_can_view_activity_logs_for_a_model()
    {
        $user = User::factory()->create();
        $activity = Activity::create([
            'log_name' => 'default',
            'description' => 'Created',
            'subject_type' => get_class($user),
            'subject_id' => $user->id,
            'causer_type' => get_class($user),
            'causer_id' => $user->id,
        ]);

        $response = $this->get(route('logs.index', ['model' => get_class($user), 'id' => $user->id]));
        $response->assertOk();
        $response->assertViewHas('logs');
    }
}
