<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    InvoiceStatus,
};

class InvoiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = 1;
        InvoiceStatus::create([
            'name' => 'Nog maken',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Controleren',
            'contextual_class' => 'light',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Wachten op versturen',
            'contextual_class' => 'secondary',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Wachten op betaling',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Herinnering 1',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Herinnering 2',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Herinnering 3',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Verlopen',
            'contextual_class' => 'warning',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Betaald',
            'contextual_class' => 'success',
            'order' => $order++,
        ]);

        InvoiceStatus::create([
            'name' => 'Geweigerd',
            'contextual_class' => 'danger',
            'order' => $order++,
        ]);
    }
}
