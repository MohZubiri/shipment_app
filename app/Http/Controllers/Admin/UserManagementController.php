<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $user = User::create($data);
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('status', 'تم إنشاء المستخدم بنجاح.');
    }

    public function edit(User $user)
    {
        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        if (!$data['password']) {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('status', 'تم تحديث المستخدم بنجاح.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('status', 'لا يمكن حذف المستخدم الحالي.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'تم حذف المستخدم بنجاح.');
    }
}
