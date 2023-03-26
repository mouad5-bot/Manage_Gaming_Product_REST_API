<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit all products']);
        Permission::create(['name' => 'edit my product']);
        Permission::create(['name' => 'delete all products']);
        Permission::create(['name' => 'delete my product']);
        Permission::create(['name' => 'read products']);
        Permission::create(['name' => 'create product']);

        
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'read categories']);
        Permission::create(['name' => 'create category']);

        
        Permission::create(['name' => 'edit profil']);
        Permission::create(['name' => 'edit my profil']);
        Permission::create(['name' => 'delete all profils']);
        Permission::create(['name' => 'delete my profil']);
        Permission::create(['name' => 'read all profiles']);
        Permission::create(['name' => 'read my profile']);

        

        // create roles and assign created permissions

        $role = Role::create(['name' => 'seller']);
        $role->givePermissionTo([
                'edit my product',
                'delete my product',
                'create product',
                'read products',
                'edit my profil',  
                'delete my profil',
                'read my profile',
    ]);

        $role = Role::create(['name' => 'user'])
            ->givePermissionTo([
                'read products',
                'edit my profil',
                'delete my profil', 
                'read my profile',
            ]);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}
