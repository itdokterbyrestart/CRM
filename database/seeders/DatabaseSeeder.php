<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed Roles and Permissions
        $this->call(PermissionSeeder::class);

        // Seed Users
        $this->call(UserSeeder::class);

        // Seed Settings
        $this->call(SettingSeeder::class);

        // Seed OrderStatus
        $this->call(OrderStatusSeeder::class);

        // Seed QuoteStatus
        $this->call(QuoteStatusSeeder::class);

        // Seed InvoiceStatus
        $this->call(InvoiceStatusSeeder::class);

        // Seed HourTypes
        $this->call(HourTypeSeeder::class);

        // Seed Products
        $this->call(ProductSeeder::class);

        // Seed Services
        $this->call(ServiceSeeder::class);

        // Seed ProductGroup
        $this->call(ProductGroupSeeder::class);

        // Seed TaxTypes
        $this->call(TaxTypeSeeder::class);
    }
}
