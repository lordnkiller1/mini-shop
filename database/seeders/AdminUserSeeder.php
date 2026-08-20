<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {

        $adminRole = Role::where('name', 'admin')
            ->firstOrFail();



        $user = User::create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make(
                env('ADMIN_PASSWORD')
            ),
        ]);


        $user->roles()->sync([
            $adminRole->id
        ]);
    }
}
