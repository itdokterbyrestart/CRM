<?php

namespace Database\Factories;

use App\Models\{
    Order,
    HourType,
    OrderHour,
    User,
    TaxType,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderHourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderHour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $durations = [15, 30, 45, 60, 75, 90, 105, 120, 135, 150, 165, 180];
        $randomKey = array_rand($durations);
        $randomDuration = $durations[$randomKey];
        
        return [
            'name' => ($hourtype = HourType::orderByRaw('RAND() * IF(id = 1, 5, 1)')->first())->name,
            'tax_percentage' => 21,
            'price_customer_excluding_tax' => $hourtype->price_customer_excluding_tax,
            'price_customer_including_tax' => $hourtype->price_customer_including_tax,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'start_time' => ($start_time = Carbon::today()->subMinutes(rand(0, 1440))->addMinutes(rand(0, 1440))->format('H:i')),
            'end_time' => ($end_time = Carbon::parse($start_time)->addMinutes($randomDuration)->format('H:i')),
            'amount' => ($amount = $this->calculateHourAmount($start_time, $end_time)),
            'amount_revenue_excluding_tax' => floatval(number_format(($amount * $hourtype->price_customer_excluding_tax), 2, '.', '')),
            'amount_revenue_including_tax' => floatval(number_format(($amount * $hourtype->price_customer_including_tax), 2, '.', '')),
            'kilometers' => ($kilometers = $this->faker->optional($weight = 0.25)->numberBetween($min = 0, $max = 100)),
            'time_minutes'=> (isset($kilometers) ? 30 : null),
            'description' => $this->faker->paragraph($nbSentences = 3, $variableNbSentences = true),
            'user_id' => User::inRandomOrder()->first()->id,
            // 'order_id' => Order::inRandomOrder()->first()->id,
            'updated_at' => Carbon::today()->subDays(rand(0,360))->format("Y-m-d H:i:s"),
            'created_at' => Carbon::today()->subDays(rand(0,360))->format("Y-m-d H:i:s"),
        ];
    }

    public function CalculateHourAmount($start_time, $end_time)
    {
        $amount = ((int)strtotime($end_time) - (int)strtotime($start_time))/60/60;
        if($amount < 0) {
            $amount = 24 + $amount;
        }
        return $amount;
    }
}
