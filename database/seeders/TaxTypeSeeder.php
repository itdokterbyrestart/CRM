<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    TaxType,
};

class TaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxType::create([
            'name' => 'Hoog',
            'percentage' => 21,
            'default' => 1,
        ]);

        TaxType::create([
            'name' => 'Laag',
            'percentage' => 9,
            'default' => 0,
        ]);

        TaxType::create([
            'name' => 'Nul',
            'percentage' => 0,
            'default' => 0,
        ]);
    }
}
