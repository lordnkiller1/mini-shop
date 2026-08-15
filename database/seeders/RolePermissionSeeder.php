<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        $permissions = [

            // Dashboard
            'view_dashboard',

            // Users
            'view_user',
            'create_user',
            'edit_user',
            'delete_user',

            // Categories
            'view_category',
            'create_category',
            'edit_category',
            'delete_category',
            'restore_category',

            // Products
            'view_product',
            'create_product',
            'edit_product',
            'delete_product',

            // Comments
            'update_comment_status',
            'delete_comment',

        ];


        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission
            ]);

        }



        $admin = Role::firstOrCreate([
            'name' => 'admin'
        ]);


        $customer = Role::firstOrCreate([
            'name' => 'customer'
        ]);



        // همه دسترسی‌ها برای ادمین

        $admin->permissions()->sync(
            Permission::pluck('id')->toArray()
        );



        // مشتری فقط مشاهده محصول

        $customer->permissions()->sync([
            Permission::where('name','view_product')
                ->first()
                ->id
        ]);

    }
}