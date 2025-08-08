<?php

namespace tests\Unit;

use App\Models\Child;
use App\Models\DivorceCase;
use App\Models\ProfileRole;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
}