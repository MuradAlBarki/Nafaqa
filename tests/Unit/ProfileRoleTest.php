<?php

namespace tests\Unit;

use App\GenderEnum;
use App\Models\Country;
use App\Models\ProfileRole;
use App\Models\User;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProfileRoleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'user_id',
            'nationality_id',
            'first_name',
            'mid_name',
            'last_name',
            'date_of_birth',
            'national_no',
            'IBAN',
            'document_type',
            'document_no',
            'document_file_url',
            'status',
            'gender'
        ];
        $this->assertEquals($fillable, (new ProfileRole())->getFillable());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => StatusEnum::class,
            'gender' => GenderEnum::class,
            'deleted_at' => 'datetime',
        ];
        $this->assertEquals($casts, (new ProfileRole())->getCasts());
    }

    #[Test]
    public function it_belongs_to_user()
    {
        $profile = ProfileRole::factory()->create();
        $this->assertInstanceOf(User::class, $profile->user);
    }

    #[Test]
    public function it_belongs_to_nationality()
    {
        $profile = ProfileRole::factory()->create();
        $this->assertInstanceOf(Country::class, $profile->nationality);
    }

    #[Test]
    public function it_has_activities()
    {
        $profile = ProfileRole::factory()->create();
        Activity::create([
            'log_name' => 'default',
            'description' => 'Created',
            'subject_type' => get_class($profile),
            'subject_id' => $profile->id,
            'causer_type' => get_class($profile->user),
            'causer_id' => $profile->user->id,
        ]);

        $this->assertInstanceOf(Activity::class, $profile->activities->first());
    }



    #[Test]
    public function it_uses_soft_deletes()
    {
        $profile = ProfileRole::factory()->create();
        $profile->delete();
        $this->assertSoftDeleted($profile);
    }
}