<?php

namespace Database\Factories;

use App\Models\{
    Order,
    Customer,
    OrderStatus,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'description' => $this->faker->optional($weight = 0.5)->paragraph($nbSentences = 3, $variableNbSentences = true),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'order_status_id' => OrderStatus::inRandomOrder()->first()->id,
            'updated_at' => Carbon::today()->subDays(rand(0,360))->format("Y-m-d H:i:s"),
            'created_at' => Carbon::today()->subDays(rand(0,360))->format("Y-m-d H:i:s"),
            'total_price_customer_excluding_tax' => 0,
            'total_tax_amount' => 0,
            'total_price_customer_including_tax' => 0,
            'total_purchase_price_excluding_tax' => 0,
            'total_profit' => 0,
        ];
    }
}