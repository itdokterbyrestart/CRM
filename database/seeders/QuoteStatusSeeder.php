<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    QuoteStatus,
};

class QuoteStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = 1;
        QuoteStatus::create([
            'name' => 'Nog maken',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        QuoteStatus::create([
            'name' => 'Wachten op versturen',
            'contextual_class' => 'secondary',
            'order' => $order++,
        ]);

        QuoteStatus::create([
            'name' => 'Wachten op klant',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        QuoteStatus::create([
            'name' => 'Akkoord',
            'contextual_class' => 'success',
            'order' => $order++,
        ]);

        QuoteStatus::create([
            'name' => 'Verlopen',
            'contextual_class' => 'warning',
            'order' => $order++,
        ]);

        QuoteStatus::create([
            'name' => 'Geweigerd',
            'contextual_class' => 'warning',
            'order' => $order++,
        ]);

        QuoteStatus::create([
            'name' => 'Verloren',
            'contextual_class' => 'danger',
            'order' => $order++,
        ]);
    }
}
