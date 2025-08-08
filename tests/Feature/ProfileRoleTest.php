<?php

namespace Tests\Feature;

use App\DocumentTypeEnum;
use App\Models\Country;
use App\Models\ProfileRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfileRoleTest extends TestCase
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

    public function test_it_can_view_profile_roles_index()
    {
        $response = $this->get(route('profile-roles.index'));
        $response->assertOk();
        $response->assertViewHas('profileRoles');
    }

    public function test_it_can_view_create_profile_role_form()
    {
        $response = $this->get(route('profile-roles.create'));
        $response->assertOk();
        $response->assertViewHas(['countries', 'documentTypes']);
    }

    public function test_it_can_delete_profile_role()
    {
        $profileRole = ProfileRole::factory()->create();
        $response = $this->delete(route('profile-roles.destroy', $profileRole));
        $response->assertRedirect(route('profile-roles.index'));
        $this->assertSoftDeleted($profileRole);
    }
}
