<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user-create',
            'user-edit',
            'user-delete',
            'user-list',

            'role-create',
            'role-list',
            'role-edit',
            'role-delete',

            'category-list',
            'category-create',
            'category-delete',
            'category-edit',

            'author-list',
            'author-create',
            'author-delete',
            'author-edit',

            'book-list',
            'book-create',
            'book-edit',
            'book-delete'
         ];
 
         foreach ($permissions as $permission) {
             Permission::create([
                 'name' => $permission,
                 'guard_name' => 'api'
             ]);
         } 
    }
}
