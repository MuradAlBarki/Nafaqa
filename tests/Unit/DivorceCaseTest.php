<?php

namespace tests\Unit;

use App\Models\Child;
use App\Models\DivorceCase;
use App\Models\ProfileRole;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Obligation;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class DivorceCaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'mother_id',
            'father_id',
            'case_no',
            'divorce_date',
            'court_document',
            'status',
        ];
        $this->assertEquals($fillable, (new DivorceCase())->getFillable());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => StatusEnum::class,
            'divorce_date' => 'date',
            'deleted_at' => 'datetime',
        ];
        $this->assertEquals($casts, (new DivorceCase())->getCasts());
    }

    #[Test]
    public function it_has_many_children()
    {
        $case = DivorceCase::factory()->create();
        Child::factory()->count(3)->create(['case_id' => $case->id]);

        $this->assertInstanceOf(Child::class, $case->children->first());
        $this->assertCount(3, $case->children);
    }

    #[Test]
    public function it_belongs_to_mother()
    {
        $case = DivorceCase::factory()->create();
        $this->assertInstanceOf(ProfileRole::class, $case->mother);
    }

    #[Test]
    public function it_belongs_to_father()
    {
        $case = DivorceCase::factory()->create();
        $this->assertInstanceOf(ProfileRole::class, $case->father);
    }

    #[Test]
    public function it_uses_soft_deletes()
    {
        $case = DivorceCase::factory()->create();
        $case->delete();
        $this->assertSoftDeleted($case);
    }

    #[Test]
    public function it_casts_status_and_date_at_runtime()
    {
        $case = DivorceCase::factory()->create([
            'status' => StatusEnum::Active,
            'divorce_date' => now(),
        ]);

        $this->assertInstanceOf(StatusEnum::class, $case->status);
        $this->assertInstanceOf(Carbon::class, $case->divorce_date);
    }

    #[Test]
    public function it_has_one_obligation()
    {
        $case = DivorceCase::factory()->create();
        $obligation = Obligation::factory()->create(['divorce_case_id' => $case->id]);

        $this->assertTrue($case->obligation->is($obligation));
    }

    #[Test]
    public function it_has_many_payments()
    {
        $case = DivorceCase::factory()->create();
        Payment::factory()->count(2)->create(['divorce_case_id' => $case->id]);

        $this->assertCount(2, $case->payments);
        $this->assertInstanceOf(Payment::class, $case->payments->first());
    }

    #[Test]
    public function it_returns_parents_collection()
    {
        $case = DivorceCase::factory()->create();
        $parents = $case->parents();

        $this->assertTrue($parents->contains($case->mother));
        $this->assertTrue($parents->contains($case->father));
    }

    #[Test]
    public function it_can_check_if_user_is_father_or_mother()
    {
        $fatherUser = User::factory()->create();
        $motherUser = User::factory()->create();

        $father = ProfileRole::factory()->create(['user_id' => $fatherUser->id]);
        $mother = ProfileRole::factory()->create(['user_id' => $motherUser->id]);

        $case = DivorceCase::factory()->create([
            'father_id' => $father->id,
            'mother_id' => $mother->id,
        ]);

        $this->assertTrue($case->isFather($fatherUser));
        $this->assertFalse($case->isFather($motherUser));

        $this->assertTrue($case->isMother($motherUser));
        $this->assertFalse($case->isMother($fatherUser));
    }

    #[Test]
    public function it_returns_event_description()
    {
        $case = DivorceCase::factory()->create();
        $description = $case->getDescriptionForEvent('created');

        $this->assertEquals("created on DivorceCase #{$case->id}", $description);
}

}