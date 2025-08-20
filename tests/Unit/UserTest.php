<?php

namespace tests\Unit;

use App\Models\ProfileRole;
use App\Models\User;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'name',
            'phone',
            'email',
            'password',
            'status'
        ];
        $this->assertEquals($fillable, (new User())->getFillable());
    }

    #[Test]
    public function it_has_hidden_attributes()
    {
        $hidden = [
            'password',
            'remember_token',
        ];
        $this->assertEquals($hidden, (new User())->getHidden());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => StatusEnum::class,
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
        $this->assertEquals($casts, (new User())->getCasts());
    }

    #[Test]
    public function it_has_one_profile_role()
    {
        $user = User::factory()->create();
        ProfileRole::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(ProfileRole::class, $user->profileRole);
    }

    #[Test]
    public function it_can_check_if_has_profile()
    {
        $userWithProfile = User::factory()->hasProfileRole()->create();
        $userWithoutProfile = User::factory()->create();

        $this->assertTrue($userWithProfile->hasProfile());
        $this->assertFalse($userWithoutProfile->hasProfile());
    }

    #[Test]
    public function it_uses_soft_deletes()
    {
        $user = User::factory()->create();
        $user->delete();
        $this->assertSoftDeleted($user);
    }

    #[Test]
    public function it_casts_status_enum_correctly()
    {
        $user = User::factory()->create(['status' => StatusEnum::Active]);
        $this->assertInstanceOf(StatusEnum::class, $user->status);
        $this->assertEquals(StatusEnum::Active, $user->status);
    }

    #[Test]
    public function it_logs_activity_on_create_update_delete()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'user',
            'event' => 'created',
            'subject_id' => $user->id,
            'subject_type' => User::class,
        ]);

        $user->update(['name' => 'Updated Name']);
        $this->assertDatabaseHas('activity_log', [
            'event' => 'updated',
            'subject_id' => $user->id,
        ]);

        $user->delete();
        $this->assertDatabaseHas('activity_log', [
            'event' => 'deleted',
            'subject_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_returns_event_description()
    {
        $user = User::factory()->create();
        $description = $user->getDescriptionForEvent('created');

        $this->assertEquals("created on User #{$user->id}", $description);
    }

}