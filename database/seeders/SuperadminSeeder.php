<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
        	'name' => 'Admin', 
        	'email' => 'superadmin@gmail.com',
            'password' => bcrypt('adminpass'),
            'user_type' => 'ADMIN'
        ]);
  
        $role = Role::create([
            'name' => 'superadmin',
            'guard_name' => 'api'
        ]);
   
        $permissions = Permission::pluck('id','id')->all();
  
        $role->syncPermissions($permissions);
   
        $user->assignRole('superadmin');
    }
}
