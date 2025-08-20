<?php

namespace tests\Unit;

use App\GenderEnum;
use App\Models\Child;
use App\Models\DivorceCase;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ChildTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'case_id',
            'first_name',
            'nationality_no',
            'date_of_birth',
            'gender',
            'status',
        ];
        $this->assertEquals($fillable, (new Child())->getFillable());
    }

    #[Test]
    public function it_has_casts()
    {
        $casts = [
            'id' => 'int',
            'status' => StatusEnum::class,
            'gender' => GenderEnum::class,
            'date_of_birth' => 'date',
            'deleted_at' => 'datetime',
        ];
        $this->assertEquals($casts, (new Child())->getCasts());
    }

    #[Test]
    public function it_belongs_to_a_divorce_case()
    {
        $child = Child::factory()->create();
        $this->assertInstanceOf(DivorceCase::class, $child->divorceCase);
    }

    #[Test]
    public function it_uses_soft_deletes()
    {
        $child = Child::factory()->create();
        $child->delete();
        $this->assertSoftDeleted($child);
    }

    #[Test]
    public function it_casts_enums_at_runtime()
    {
        $child = Child::factory()->create([
            'status' => StatusEnum::Active,
            'gender' => GenderEnum::Male,
        ]);

        $this->assertInstanceOf(StatusEnum::class, $child->status);
        $this->assertEquals(StatusEnum::Active, $child->status);

        $this->assertInstanceOf(GenderEnum::class, $child->gender);
        $this->assertEquals(GenderEnum::Male, $child->gender);
    }

    #[Test]
    public function it_returns_event_description()
    {
        $child = Child::factory()->create();
        $description = $child->getDescriptionForEvent('created');

        $this->assertEquals("created on Child #{$child->id}", $description);
    }

    #[Test]
    public function it_includes_birth_certificate_url_when_set()
    {
        $child = Child::factory()->create([
            'birth_certificate_url' => 'https://example.com/certificate.pdf'
        ]);

        $this->assertEquals('https://example.com/certificate.pdf', $child->birth_certificate_url);
    }

}