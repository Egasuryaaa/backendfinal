<?php

namespace Database\Seeders;

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
        // Buat role jika belum ada
        $user = Role::firstOrCreate(['name' => 'user'], ['guard_name' => 'web']);
        $seller = Role::firstOrCreate(['name' => 'seller'], ['guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);

        // Buat permissions jika belum ada
        $manageProducts = Permission::firstOrCreate(['name' => 'manage products'], ['guard_name' => 'web']);
        $manageOrders = Permission::firstOrCreate(['name' => 'manage orders'], ['guard_name' => 'web']);
        $manageUsers = Permission::firstOrCreate(['name' => 'manage users'], ['guard_name' => 'web']);
        $manageSellers = Permission::firstOrCreate(['name' => 'manage sellers'], ['guard_name' => 'web']);
        $manageLocations = Permission::firstOrCreate(['name' => 'manage locations'], ['guard_name' => 'web']);
        $manageAppointments = Permission::firstOrCreate(['name' => 'manage appointments'], ['guard_name' => 'web']);
        $viewReports = Permission::firstOrCreate(['name' => 'view reports'], ['guard_name' => 'web']);

        // User permissions (customer)
        $user->syncPermissions([]);

        // Seller permissions
        $seller->syncPermissions([
            $manageProducts,
            $manageOrders,
            $manageLocations,
            $manageAppointments,
        ]);

        // Admin permissions (semua)
        $admin->syncPermissions([
            $manageProducts,
            $manageOrders,
            $manageUsers,
            $manageSellers,
            $manageLocations,
            $manageAppointments,
            $viewReports,
        ]);
    }
}
