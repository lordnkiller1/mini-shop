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

        $adminRole = Role::where('name','admin')
            ->firstOrFail();



        $user = User::firstOrCreate(

            [
                'email' => 'admin@gmail.com'
            ],

            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ]

        );


        $user->roles()->sync([
            $adminRole->id
        ]);

    }
}