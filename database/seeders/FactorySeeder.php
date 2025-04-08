<?php

namespace Database\Seeders;

use App\Models\{
    Customer,
    Order,
    OrderHour,
    OrderProduct,
};

use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Factory 10 customers
        Customer::factory()
            ->count(30)
            ->create();

        // Factory 50 deals
        Order::factory()
            ->count(300)
            ->has(OrderHour::factory()->count(3), 'order_hours')
            ->has(OrderProduct::factory()->count(3), 'order_products')
            ->create();
    }
}
