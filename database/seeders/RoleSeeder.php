<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define roles and their descriptions
        $roles = [
            [
                'name' => 'admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pembeli',
                'guard_name' => 'web'
            ],
            [
                'name' => 'penjual_biasa',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pengepul',
                'guard_name' => 'web'
            ],
            [
                'name' => 'pemilik_tambak',
                'guard_name' => 'web'
            ]
        ];

        // Create sanctum guard versions too for API
        $sanctumRoles = [];
        foreach ($roles as $role) {
            $sanctumRoles[] = array_merge($role, ['guard_name' => 'sanctum']);
        }

        $allRoles = array_merge($roles, $sanctumRoles);

        // Create or update roles
        foreach ($allRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                $roleData
            );
            
            echo "Role '{$role->name}' ({$role->guard_name}) created/updated.\n";
        }

        // Create permissions for each role
        $this->createPermissions();
        
        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        echo "Role seeder completed successfully!\n";
    }

    /**
     * Create permissions for the system
     */
    private function createPermissions(): void
    {
        $permissions = [
            // Admin permissions
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_admin_panel',
            'manage_system_settings',
            'view_all_orders',
            'manage_categories',
            'manage_payments',
            
            // Product permissions
            'create_products',
            'edit_products',
            'delete_products',
            'view_products',
            'manage_own_products',
            
            // Order permissions
            'create_orders',
            'view_orders',
            'manage_orders',
            'view_own_orders',
            'update_order_status',
            
            // Cart permissions
            'manage_cart',
            'checkout',
            
            // Profile permissions
            'edit_profile',
            'view_profile',
            
            // Address permissions
            'manage_addresses',
            
            // Appointment permissions
            'create_appointments',
            'view_appointments',
            'manage_appointments',
            'view_own_appointments',
            
            // Review permissions
            'create_reviews',
            'view_reviews',
            'manage_reviews',
            
            // Tambak/Production permissions
            'manage_tambak',
            'view_production_data',
            'manage_harvest',
            
            // Pengepul permissions
            'bulk_purchase',
            'manage_suppliers',
            'view_supply_chain',
            
            // Location permissions
            'manage_locations',
            'view_seller_locations'
        ];

        // Create permissions for both web and sanctum guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }

        echo "Permissions created successfully!\n";
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        $this->assignPermissionsForGuard('web');
        $this->assignPermissionsForGuard('sanctum');
    }

    /**
     * Assign permissions for specific guard
     */
    private function assignPermissionsForGuard(string $guard): void
    {
        // Admin - Full access
        $admin = Role::where('name', 'admin')->where('guard_name', $guard)->first();
        if ($admin) {
            $admin->givePermissionTo(Permission::where('guard_name', $guard)->get());
        }

        // Pembeli (User/Customer) - Basic customer permissions
        $pembeli = Role::where('name', 'pembeli')->where('guard_name', $guard)->first();
        if ($pembeli) {
            $pembeli->givePermissionTo([
                'view_products',
                'create_orders',
                'view_own_orders',
                'manage_cart',
                'checkout',
                'edit_profile',
                'view_profile',
                'manage_addresses',
                'create_appointments',
                'view_own_appointments',
                'create_reviews',
                'view_reviews'
            ]);
        }

        // Penjual Biasa - Can sell products
        $penjualBiasa = Role::where('name', 'penjual_biasa')->where('guard_name', $guard)->first();
        if ($penjualBiasa) {
            $penjualBiasa->givePermissionTo([
                'view_products',
                'create_products',
                'edit_products',
                'manage_own_products',
                'view_orders',
                'manage_orders',
                'update_order_status',
                'edit_profile',
                'view_profile',
                'manage_addresses',
                'view_appointments',
                'manage_appointments',
                'view_reviews',
                'manage_locations',
                'view_seller_locations',
                
                // Also buyer permissions
                'create_orders',
                'view_own_orders',
                'manage_cart',
                'checkout',
                'create_appointments',
                'view_own_appointments',
                'create_reviews'
            ]);
        }

        // Pengepul - Bulk purchasing and supply chain management
        $pengepul = Role::where('name', 'pengepul')->where('guard_name', $guard)->first();
        if ($pengepul) {
            $pengepul->givePermissionTo([
                'view_products',
                'bulk_purchase',
                'manage_suppliers',
                'view_supply_chain',
                'create_orders',
                'view_orders',
                'view_own_orders',
                'edit_profile',
                'view_profile',
                'manage_addresses',
                'create_appointments',
                'view_appointments',
                'manage_appointments',
                'view_reviews',
                'manage_locations'
            ]);
        }

        // Pemilik Tambak - Production and harvest management
        $pemilikTambak = Role::where('name', 'pemilik_tambak')->where('guard_name', $guard)->first();
        if ($pemilikTambak) {
            $pemilikTambak->givePermissionTo([
                'view_products',
                'create_products',
                'edit_products',
                'manage_own_products',
                'manage_tambak',
                'view_production_data',
                'manage_harvest',
                'view_orders',
                'manage_orders',
                'update_order_status',
                'edit_profile',
                'view_profile',
                'manage_addresses',
                'view_appointments',
                'manage_appointments',
                'view_reviews',
                'manage_locations'
            ]);
        }

        echo "Permissions assigned to roles for {$guard} guard successfully!\n";
    }
}
