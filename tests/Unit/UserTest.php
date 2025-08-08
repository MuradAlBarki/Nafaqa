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
}