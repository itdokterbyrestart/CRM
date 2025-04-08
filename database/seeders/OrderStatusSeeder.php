<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    OrderStatus,
};

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = 1;
        OrderStatus::create([
            'name' => 'Nog doen',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);       

        OrderStatus::create([
            'name' => 'Bellen',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Offerte sturen',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Evalueren',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Wachten op klant',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Wachten op Thomas',
            'contextual_class' => 'warning',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Wachten op datum',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Bestellen',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Wachten op bestelling',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Afspraak maken',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Optreden gepland',
            'contextual_class' => 'light',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Afspraak gemaakt / Ophalen / Plaatsen',
            'contextual_class' => 'light',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Factureren',
            'contextual_class' => 'primary',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Wachten op betaling',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Uitnodiging gestuurd',
            'contextual_class' => 'info',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Betaald, archief',
            'contextual_class' => 'success',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Verloren',
            'contextual_class' => 'danger',
            'order' => $order++,
        ]);

        OrderStatus::create([
            'name' => 'Gratis',
            'contextual_class' => 'default',
            'order' => $order++,
        ]);
    }
}
