<?php

namespace Tests\Unit;

use App\Models\DivorceCase;
use App\Models\Obligation;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ObligationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'divorce_case_id',
            'amount',
            'start_date',
            'end_date',
            'status',
        ];
        $this->assertEquals($fillable, (new Obligation())->getFillable());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => StatusEnum::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
        $this->assertEquals($casts, (new Obligation())->getCasts());
    }

    #[Test]
    public function it_belongs_to_a_divorce_case()
    {
        $case = DivorceCase::factory()->create();
        $obligation = Obligation::factory()->create(['divorce_case_id' => $case->id]);

        $this->assertInstanceOf(DivorceCase::class, $obligation->divorceCase);
        $this->assertEquals($case->id, $obligation->divorceCase->id);
    }

    #[Test]
    public function it_casts_status_at_runtime()
    {
        $obligation = Obligation::factory()->create(['status' => StatusEnum::Active]);

        $this->assertInstanceOf(StatusEnum::class, $obligation->status);
        $this->assertEquals(StatusEnum::Active, $obligation->status);
    }

    #[Test]
    public function it_returns_event_description()
    {
        $obligation = Obligation::factory()->create();
        $description = $obligation->getDescriptionForEvent('created');

        $this->assertEquals("created on Obligation #{$obligation->id}", $description);
    }
}
