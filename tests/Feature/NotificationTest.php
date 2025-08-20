<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_it_can_mark_notification_as_read()
    {
        $user = auth()->user();
        $notification = DatabaseNotification::create([
            'id' => \Str::uuid()->toString(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => get_class($user),
            'notifiable_id' => $user->id,
            'data' => ['message' => 'Test notification'],
        ]);

        $response = $this->get(route('notifications.read', $notification->id));
        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }
}
