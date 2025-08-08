<?php

namespace Tests\Feature;

use App\GenderEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\DivorceCase;
use App\Models\ProfileRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DivorceCaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        
        // Then create and assign role
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
        Storage::fake('public');
    }

    
    public function test_it_can_view_divorce_cases_index()
    {
        $response = $this->get(route('divorce-cases.index'));
        $response->assertOk();
        $response->assertViewHas('divorceCases');
    }

    
   public function test_it_can_create_divorce_case()
{
    $mother = ProfileRole::factory()->create(['gender' => GenderEnum::Female->value]);
    $father = ProfileRole::factory()->create(['gender' => GenderEnum::Male->value]);

    $response = $this->post(route('divorce-cases.store'), [
        'mother_id' => $mother->id,
        'father_id' => $father->id,
        'case_no' => 'CASE-123',
        'divorce_date' => '2023-01-01',
        'court_document' => UploadedFile::fake()->create('document.pdf'),
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('divorce_cases', ['case_no' => 'CASE-123']);
}

    
    public function test_it_can_update_divorce_case()
    {
        $case = DivorceCase::factory()->create();
        $response = $this->put(route('divorce-cases.update', $case), [
            'mother_id' => $case->mother_id,
            'father_id' => $case->father_id,
            'case_no' => 'UPDATED-123',
            'divorce_date' => '2023-01-01',
        ]);

        $response->assertRedirect();
        $this->assertEquals('UPDATED-123', $case->fresh()->case_no);
    }

    
    public function test_it_can_delete_divorce_case()
    {
        $case = DivorceCase::factory()->create();
        $response = $this->delete(route('divorce-cases.destroy', $case));
        $response->assertRedirect();
        $this->assertSoftDeleted($case);
    }
}
