<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\StatusEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with(['profileRole', 'roles', 'permissions'])->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', User::class);

        $allPermissions = Permission::orderBy('name')->get();

        $groupedPermissions = $allPermissions->groupBy(function ($perm) {
        return explode('.', $perm->name)[0]; 
        });

        return view('users.edit', compact('user', 'groupedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', User::class);

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->syncPermissions($request->permissions ?? []);

         activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Change Permissions');

        return redirect()->route('users.index')->with('success', __('Permissions updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);
        $user->delete();

                activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Deleted');
        return redirect()->route('users.index')->with('success', __('deleted successfully.'));
    }


    public function toggleStatus(User $user)
    {
        $user->status == StatusEnum::Active ?  $user->status = StatusEnum::Inactive : $user->status = StatusEnum::Active ;
        $user->save();

                        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Status');

        return redirect()->route('users.index');
    }
}
