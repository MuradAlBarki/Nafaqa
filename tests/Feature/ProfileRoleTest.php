<?php

namespace Tests\Feature;

use App\DocumentTypeEnum;
use App\Models\Country;
use App\Models\ProfileRole;
use App\Models\User;
use App\Notifications\UserAlertNotification;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
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

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        Storage::fake('public');
        Notification::fake();
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

    public function test_it_can_view_single_profile_role()
    {
        $profileRole = ProfileRole::factory()->create();
        $response = $this->get(route('profile-roles.show', $profileRole));
        $response->assertOk();
        $response->assertViewHas(['profileRole', 'nationalityName']);
    }

    public function test_it_can_view_edit_profile_role_form()
    {
        $profileRole = ProfileRole::factory()->create();
        $response = $this->get(route('profile-roles.edit', $profileRole));
        $response->assertOk();
        $response->assertViewHas(['profileRole', 'countries', 'documentTypes']);
    }



    public function test_it_can_view_review_page()
    {
        $profileRole = ProfileRole::factory()->create();
        $response = $this->get(route('profile-roles.show-review', $profileRole));
        $response->assertOk();
        $response->assertViewHas(['profileRole', 'nationalityName']);
    }

    public function test_it_can_review_profile_role_status()
    {
        $profileRole = ProfileRole::factory()->create();
        $status = StatusEnum::Active->value;

        $response = $this->patch(route('profile-roles.review', $profileRole), [
            'status' => $status
        ]);

        $response->assertRedirect(route('profile-roles.index'));
        $this->assertEquals($status, $profileRole->fresh()->status->value);

        Notification::assertSentTo($profileRole->user, \App\Notifications\UserAlertNotification::class);
    }

    public function test_it_can_delete_profile_role()
    {
        $profileRole = ProfileRole::factory()->create();
        $response = $this->delete(route('profile-roles.destroy', $profileRole));

        $response->assertRedirect(route('profile-roles.index'));
        $this->assertSoftDeleted($profileRole);
    }

    public function test_it_can_export_profile_roles()
    {
        $response = $this->get(route('profile-roles.export'));
        $response->assertOk();
        $response->assertHeader('content-disposition');
    }
}
