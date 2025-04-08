<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    ProductGroup,
};

class ProductGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = 1;
        ProductGroup::create([
            'name' => 'Laptop',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'Office',
            'order' => $order++,
        ]);
        
        ProductGroup::create([
            'name' => 'Laptop optioneel',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'Laptop speciaal',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'iPad',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'iPad optioneel',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'Office iPad',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'iPhone',
            'order' => $order++,
        ]);

        ProductGroup::create([
            'name' => 'iPhone optioneel',
            'order' => $order++,
        ]);
    }
}
