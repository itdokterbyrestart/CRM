<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Service,
};

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'APK op afstand',
            'product_id' => 1,
        ]);

        Service::create([
            'name' => 'APK aan huis',
            'product_id' => 1,
        ]);

        Service::create([
            'name' => 'Backup',
            'product_id' => 1,
        ]);
    }
}
