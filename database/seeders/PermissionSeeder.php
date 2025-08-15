<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = ['viewAny', 'view', 'create', 'update', 'changeStatus', 'delete'];
        $features = ['users', 'profileRoles', 'divorceCases', 'children', 'obligations', 'payments'];

        $allPermissionNames = [];

        foreach ($features as $feature) {
            foreach ($permissions as $action) {
                $permissionName = "{$feature}.{$action}";
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
                $allPermissionNames[] = $permissionName;
            }
        }

   
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($allPermissionNames); 

        $admin = User::firstOrCreate(
            ['phone' => '0912444693'],
            [
                'name' => 'مراد البركي',
                'email' => 'pe.murad@gmail.com',
                'password' => bcrypt('@password'),
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        User::firstOrCreate(
            ['phone' => '0954440744'],
            [
                'name' => 'محمد الخبولي',
                'email' => 'test@test.com',
                'password' => bcrypt('@password'),
            ]);

        User::firstOrCreate(
            ['phone' => '0943383941'],
            [
                'name' => 'الاء حسين',
                'email' => 'arth@test.com',
                'password' => bcrypt('@password'),
            ]);
    
    }
}
