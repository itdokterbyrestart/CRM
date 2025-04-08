<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'show dashboard']);
        Permission::create(['name' => 'show revenue']);
        Permission::create(['name' => 'show profit']);
        Permission::create(['name' => 'show hours']);
        Permission::create(['name' => 'show costs']);
        Permission::create(['name' => 'show menu item']);
        Permission::create(['name' => 'show management']);
        Permission::create(['name' => 'delete order']);
        Permission::create(['name' => 'delete customer']);
        Permission::create(['name' => 'edit archived']);
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);
        Permission::create(['name' => 'delete quote']);
        Permission::create(['name' => 'edit quote archived']);
        Permission::create(['name' => 'manage taxtypes']);
        Permission::create(['name' => 'manage productgroups']);
        Permission::create(['name' => 'manage quotestatuses']);
        Permission::create(['name' => 'manage orderstatuses']);
        Permission::create(['name' => 'manage hourtypes']);
        Permission::create(['name' => 'delete selectedquoteproduct']);
        Permission::create(['name' => 'delete invoice']);
        Permission::create(['name' => 'edit invoice archived']);
        Permission::create(['name' => 'manage invoicestatuses']);
        Permission::create(['name' => 'manage services']);
        Permission::create(['name' => 'manage settings']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'delete user']);

        
        // Create role admin
        Role::create(['name' => 'admin'])->givePermissionTo([
            'show dashboard',
            'show revenue',
            'show profit',
            'show costs',
            'show hours',
            'show menu item',
            'show management',
            'delete order',
            'delete customer',
            'edit archived',
            'manage roles',
            'manage permissions',
            'delete quote',
            'edit quote archived',
            'manage taxtypes',
            'manage productgroups',
            'manage quotestatuses',
            'manage orderstatuses',
            'manage hourtypes',
            'delete selectedquoteproduct',
            'delete invoice',
            'edit invoice archived',
            'manage invoicestatuses',
            'manage services',
            'manage settings',
            'manage users',
            'delete user',
        ]);

        // Create role user
        Role::create(['name' => 'user'])->givePermissionTo([
            'show menu item',
        ]);
    }
}
