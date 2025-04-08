<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Product,
};

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Products
            $ipad_description = "Kleur: spacegrijs\r\nOpslag geheugen: 128GB\r\nWebcam: Ja\r\nWifi: Ja\r\nMobiel Internet: Nee";
            Product::create([
                'name' => 'iPad 10,2 inch (2020)',
                'description' => $ipad_description,
                'purchase_price_excluding_tax' => 321.50,
                'purchase_price_including_tax' => 389.02,
                'price_customer_excluding_tax' => 537.19,
                'price_customer_including_tax' => 650,
                'profit' => 215.69,
                'tax_percentage' => 21,
                'link' => 'https://www.bol.com/nl/nl/p/apple-ipad-10-2-inch-wifi-128gb-spacegrijs/9300000010357490/',
            ]);

            Product::create([
                'name' => 'iPad Pro 11 inch (2021)',
                'description' => $ipad_description,
                'purchase_price_excluding_tax' => 742.98,
                'purchase_price_including_tax' => 899.01,
                'price_customer_excluding_tax' => 867.77,
                'price_customer_including_tax' => 1050,
                'profit' => 124.79,
                'tax_percentage' => 21,
                'link' => 'https://www.apple.com/nl/shop/buy-ipad/ipad-pro',
            ]);

            Product::create([
                'name' => 'iPad Pro 12,9 inch (2021)',
                'description' => $ipad_description,
                'purchase_price_excluding_tax' => 1007.44,
                'purchase_price_including_tax' => 1219.00,
                'price_customer_excluding_tax' => 1115.70,
                'price_customer_including_tax' => 1350,
                'profit' => 108.26,
                'tax_percentage' => 21,
                'link' => 'https://www.apple.com/nl/shop/buy-ipad/ipad-pro',
            ]);

            $laptop_description = "Processor: Intel Core i5\r\nIntern geheugen: 8GB\r\nOpslag geheugen: 500GB SSD\r\nDVD-Brander: Nee\r\nCardreader: Ja\r\nWebcam: Ja\r\nWindows: 10 Home (met licentie t.w.v. 100 euro)\r\nOffice: Open Office";
            Product::create([
                'name' => 'Laptop',
                'description' => $laptop_description,
                'purchase_price_excluding_tax' => 495.87,
                'purchase_price_including_tax' => 600.00,
                'price_customer_excluding_tax' => 661.16,
                'price_customer_including_tax' => 800,
                'profit' => 165.29,
                'tax_percentage' => 21,
                'link' => 'https://www.apple.com/nl/shop/buy-ipad/ipad-pro',
            ]);

            Product::create([
                'name' => 'Microsoft 365 Personal',
                'description' => '',
                'purchase_price_excluding_tax' => 20.66,
                'purchase_price_including_tax' => 25,
                'price_customer_excluding_tax' => 53.72,
                'price_customer_including_tax' => 65,
                'profit' => 33.06,
                'tax_percentage' => 21,
                'link' => '',
            ]);

            Product::create([
                'name' => 'Microsoft 365 Family',
                'description' => '',
                'purchase_price_excluding_tax' => 51.09,
                'purchase_price_including_tax' => 61.82,
                'price_customer_excluding_tax' => 82.64,
                'price_customer_including_tax' => 100,
                'profit' => 31.55,
                'tax_percentage' => 21,
                'link' => '',
            ]);

            Product::create([
                'name' => 'APK op afstand',
                'description' => '',
                'purchase_price_excluding_tax' => 0,
                'purchase_price_including_tax' => 0,
                'price_customer_excluding_tax' => 53.72,
                'price_customer_including_tax' => 65,
                'profit' => 53.72,
                'tax_percentage' => 21,
                'link' => '',
            ]);

            Product::create([
                'name' => 'APK aan huis',
                'description' => '',
                'purchase_price_excluding_tax' => 0,
                'purchase_price_including_tax' => 0,
                'price_customer_excluding_tax' => 103.31,
                'price_customer_including_tax' => 125,
                'profit' => 103.31,
                'tax_percentage' => 21,
                'link' => '',
            ]);
    }
}
