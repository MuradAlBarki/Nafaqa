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
}