<?php

namespace Tests\Feature;

use App\GenderEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Child;
use App\Models\DivorceCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

class ChildTest extends TestCase
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
    }

    
    public function test_it_can_view_children_index()
    {
        $case = DivorceCase::factory()->create();
        $response = $this->get(route('divorce-cases.children.index', $case));
        $response->assertOk();
        $response->assertViewHas(['divorceCase', 'children']);
    }

    
    public function test_it_can_create_child()
    {
        $case = DivorceCase::factory()->create();
        $file = UploadedFile::fake()->create('birth_certificate.pdf');

    $response = $this->post(route('divorce-cases.children.store', $case), [
        'first_name' => 'John',
        'date_of_birth' => '2020-01-01',
        'gender' => GenderEnum::Male->value,
        'birth_certificate' => $file, // match controller
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('children', [
        'case_id' => $case->id,
        'first_name' => 'John',
    ]);

    }

    
    public function test_it_can_update_child()
    {
        $case = DivorceCase::factory()->create();
        $child = Child::factory()->create(['case_id' => $case->id]);

        $response = $this->patch(route('divorce-cases.children.update', [$case, $child]), [
            'first_name' => 'Updated',
            'last_name' => $child->last_name,
            'date_of_birth' => $child->date_of_birth,
            'gender' => $child->gender->value,
            'birth_certificate_url' => $child->birth_certificate_url,
        ]);


        $response->assertRedirect();
        $this->assertEquals('Updated', $child->fresh()->first_name);

    }

    
    public function test_it_can_delete_child()
    {
        $case = DivorceCase::factory()->create();
        $child = Child::factory()->create(['case_id' => $case->id]);

        $response = $this->delete(route('divorce-cases.children.destroy', [$case, $child]));
        $response->assertRedirect();
        $this->assertSoftDeleted($child);
    }
}
