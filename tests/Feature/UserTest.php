<?php

namespace Tests\Feature;

use App\Models\User;
use App\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
         Role::create(['name' => 'admin']);
        
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
    }

    public function test_it_can_view_users_index()
    {
        $response = $this->get(route('users.index'));
        $response->assertOk();
        $response->assertViewHas('users');
    }

    public function test_it_can_view_user_edit_page()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.edit', $user));
        $response->assertOk();
        $response->assertViewHas(['user', 'groupedPermissions']);
    }

    public function test_it_can_update_user_permissions()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'test.permission']);

        $response = $this->put(route('users.update', $user), [
            'permissions' => ['test.permission']
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertTrue($user->hasPermissionTo('test.permission'));
    }

    public function test_it_can_delete_user()
    {
        $user = User::factory()->create();
        $response = $this->delete(route('users.destroy', $user));
        $response->assertRedirect(route('users.index'));
        $this->assertSoftDeleted($user);
    }

    public function test_it_can_toggle_user_status()
    {
        $user = User::factory()->create(['status' => StatusEnum::Active]);
        $response = $this->patch(route('users.toggleStatus', $user));
        $this->assertEquals(StatusEnum::Inactive, $user->fresh()->status);
    }
}
