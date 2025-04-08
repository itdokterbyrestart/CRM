<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new CustomersImport, storage_path('csv/Customers.csv'));
    }
}
