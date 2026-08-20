<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserRequest as RequestsStoreUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(
                'permission:create_user',
                only: ['create', 'store']
            ),
            new Middleware(
                'permission:view_user',
                only: ['index']
            ),

            new Middleware(
                'permission:edit_user',
                only: ['edit', 'update']
            ),

            new Middleware(
                'permission:delete_user',
                only: ['destroy']
            ),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->filter($request->only([
                'search',
                'role_id',
            ]))
            ->paginate(10)
            ->withQueryString();


        $roles = Role::all();


        return view('users.index', compact(
            'users',
            'roles'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->sync($data['roles'] ?? []);

        return to_route('users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        
        $currentIsAdmin = $user->roles()
            ->where('name', 'admin')
            ->exists();


        $newRoleIsAdmin = Role::where('id', $request->role)
            ->where('name', 'admin')
            ->exists();


        if ($currentIsAdmin && ! $newRoleIsAdmin) {

            $adminCount = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();


            if ($adminCount <= 1) {

                return back()->with(
                    'error',
                    'آخرین مدیر سیستم نمی‌تواند نقش خود را تغییر دهد'
                );
            }
        }


        
        $user->roles()->sync($data['roles'] ?? []);

        return to_route('users.index')->with('success', 'کاربر با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $isAdmin = $user->roles()
            ->where('name', 'admin')
            ->exists();


        if ($isAdmin) {

            $adminCount = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();


            if ($adminCount <= 1) {

                return back()->with(
                    'error',
                    'آخرین مدیر سیستم قابل حذف نیست'
                );
            }
        }


        $user->delete();


        return back()->with(
            'success',
            'کاربر حذف شد'
        );
    }
}
