<?php

namespace Database\Factories;

use App\Models\{
    Order,
    Product,
    OrderProduct,
    User,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ($product = Product::inRandomOrder()->first())->name,
            'description' => $this->faker->optional($weight = 0.5)->paragraph($nbSentences = 3, $variableNbSentences = true),
            'purchase_price_excluding_tax' => ($purchase_price_excluding_tax = $product->purchase_price_excluding_tax),
            'purchase_price_including_tax' => ($purchase_price_including_tax = $product->purchase_price_including_tax),
            'price_customer_excluding_tax' => ($price_customer_excluding_tax = $product->price_customer_excluding_tax),
            'price_customer_including_tax' => ($price_customer_including_tax = $product->price_customer_including_tax),
            'amount' => ($amount = rand(1,3)),
            'revenue' => ($revenue = (float)$price_customer_excluding_tax * (float)$amount),
            'tax_percentage' => 21,
            'profit' => ((float)$revenue - ((float)$amount * (float)$purchase_price_excluding_tax)),
            'supplier' => ($supplier = $this->faker->optional($weight = 0.5)->word()),
            'total_price_customer_including_tax' => ((float)$amount * (float)$price_customer_including_tax),
            'total_purchase_price_excluding_tax' => ((float)$amount * (float)$purchase_price_excluding_tax),
            'order_number' => (isset($supplier) ? $this->faker->numerify('##########') : null),
            'user_id' => (isset($supplier) ? User::inRandomOrder()->first()->id : null),
            // 'order_id' => Order::inRandomOrder()->first()->id,
            'updated_at' => Carbon::today()->subDays(rand(0,360))->format("Y-m-d H:i:s"),
            'created_at' => Carbon::today()->subDays(rand(0,360))->format("Y-m-d H:i:s"),
        ];
    }
}
