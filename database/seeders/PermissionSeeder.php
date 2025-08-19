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

        $extraPermissions = [
            'epayments.viewAny',
            'epayments.view',
            'reports.download',
            'logs.viewAny',
        ];

        $allPermissionNames = [];

        foreach ($features as $feature) {
            foreach ($permissions as $action) {
                $allPermissionNames[] = "{$feature}.{$action}";
            }
        }

        $allPermissionNames = array_merge($allPermissionNames, $extraPermissions);


        foreach ($allPermissionNames as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
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
                'email' => 'moopzaad@gmail.com',
                'password' => bcrypt('@password'),
            ]);

        User::firstOrCreate(
            ['phone' => '0943383941'],
            [
                'name' => 'الاء حسين',
                'email' => 'mb2103005@cctt.edu.ly',
                'password' => bcrypt('@password'),
            ]);
    
    }
}
