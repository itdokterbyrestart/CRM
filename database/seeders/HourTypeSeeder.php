<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    HourType,
};

class HourTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HourType::create([
            'name' => 'Standaard',
            'price_customer_excluding_tax' => 33.06,
            'price_customer_including_tax' => 40,
            'tax_percentage' => 21,
        ]);

        HourType::create([
            'name' => 'Product',
            'price_customer_excluding_tax' => 0,
            'price_customer_including_tax' => 0,
            'tax_percentage' => 21,
        ]);

        HourType::create([
            'name' => 'Service',
            'price_customer_excluding_tax' => 0,
            'price_customer_including_tax' => 0,
            'tax_percentage' => 21,
        ]);

        HourType::create([
            'name' => 'Halve prijs',
            'price_customer_excluding_tax' => 16.53,
            'price_customer_including_tax' => 20,
            'tax_percentage' => 21,
        ]);

        HourType::create([
            'name' => 'Gratis',
            'price_customer_excluding_tax' => 0,
            'price_customer_including_tax' => 0,
            'tax_percentage' => 21,
        ]);

        HourType::create([
            'name' => 'Bellen',
            'price_customer_excluding_tax' => 0,
            'price_customer_including_tax' => 0,
            'tax_percentage' => 21,
        ]);
    }
}
