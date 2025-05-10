<?php

namespace App\Http\Controllers;

use App\Models\{
    Customer,
    OrderHour,
    OrderProduct,
    Order,
    Product,
    Setting,
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('show dashboard')) {
            return redirect(route('profile.show'));
        }

        $start_date = (isset($_GET['start_date']) ? Carbon::parse($_GET['start_date']) : Carbon::now()->subDays(7))->startOfDay();
        $end_date = (isset($_GET['end_date']) ? Carbon::parse($_GET['end_date']) : Carbon::now())->endOfDay();

        $total_hours_revenue = 
            OrderHour::query()
                ->whereBetween('date',[$start_date,$end_date])
                ->sum('amount_revenue_excluding_tax');

        $total_hours = 
            OrderHour::query()
                ->whereBetween('date',[$start_date,$end_date])
                ->sum('amount');


        $total_products_costs = 
            OrderProduct::query()
                ->whereBetween('created_at',[$start_date,$end_date])
                ->sum('purchase_price_excluding_tax');

        $total_products_revenue = 
            OrderProduct::query()
                ->whereBetween('created_at',[$start_date,$end_date])
                ->sum('revenue');

        $total_revenue = ($total_hours_revenue + $total_products_revenue);

        $total_products_profit = 
            OrderProduct::query()
                ->whereBetween('created_at',[$start_date,$end_date])
                ->sum('profit');

        $total_cost_hours_other_users = 
            (OrderHour::query()
                ->whereBetween('date',[$start_date,$end_date])
                ->whereNot('user_id', Auth::user()->id)
                ->sum('amount') * Setting::where('name', 'hour_cost_other_users')->first()->value ?? 20);

        $total_cost = ($total_products_costs + $total_cost_hours_other_users);

        $total_hours_profit = ($total_hours_revenue - $total_cost_hours_other_users);

        $total_profit = ($total_products_profit + $total_hours_revenue - $total_cost_hours_other_users);

        $order_hour_amount_sum = 
            OrderHour::query()
                ->whereBetween('created_at',[$start_date,$end_date])
                ->sum('amount');

        $order_product_count = 
            OrderProduct::query()
                ->whereBetween('created_at',[$start_date,$end_date])
                ->count();

        $order_amount_count = 
            Order::query()
                ->whereBetween('created_at',[$start_date,$end_date])
                ->count();

        $unique_customer_count = 
            Customer::query()
                ->whereHas('orders', function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('created_at',[$start_date,$end_date]);
                })
                ->count();

        $mean_profit_per_hour = ($total_hours != 0) ?  ($total_profit/$total_hours) : 0;

        $product_margin_sold_products = ($total_products_revenue != 0) ? (($total_products_revenue - $total_products_costs) / $total_products_revenue * 100) : 0;

        $order_products_count = Order::query()
            ->whereBetween('created_at',[$start_date,$end_date])
            ->select('id')
            ->withCount('order_products')
            ->get();

        $mean_order_products_count_per_order = $order_products_count->average('order_products_count');

        $order_hours_amount = Order::query()
            ->whereBetween('created_at',[$start_date,$end_date])
            ->select('id')
            ->withSum('order_hours','amount')
            ->get();

        $mean_order_hours_amount_per_order = $order_hours_amount->average('order_hours_sum_amount');

        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Index"]
        ];

        $start_date_input = (date("Y-m-d",strtotime($start_date)));
        $end_date_input = (date("Y-m-d",strtotime($end_date)));

        return view('content.dashboard.index', compact('breadcrumbs','start_date_input','end_date_input','total_hours_revenue','total_revenue','total_products_costs','total_products_revenue','total_products_profit','total_cost_hours_other_users','total_cost','total_hours_profit','total_profit','order_hour_amount_sum','order_product_count','order_amount_count','unique_customer_count','mean_profit_per_hour','product_margin_sold_products','mean_order_products_count_per_order','mean_order_hours_amount_per_order'));
    }

    public function date_change(Request $request)
    {
        $request->validate([
            'start_date_input' => 'nullable|date|before:end_date_input',
            'end_date_input' => 'nullable|date|after:start_date_input',
        ]);

        $URL = 'start_date=' . $request->start_date_input . '&end_date=' . $request->end_date_input;

        return redirect(route('dashboard', $URL));
    }

    public function factories()
    {
        // Remove all orders that dont have an invoice created 1 year before today
        $orders = Order::whereDate('created_at', '>=', Carbon::now()->subYears(1)->startOfDay()->toDateTimeString())->whereDoesntHave('invoices')->delete();

        // Create factories
        // $orders = Order::factory()
        //     ->count(150)
        //     ->has(OrderProduct::factory()->count(random_int(1, 5)), 'order_products')
        //     ->has(OrderHour::factory()->count(random_int(1, 5)), 'order_hours')
        //     ->create();

        // Artisan::call('calculate_order_prices_and_profits_and_totals');

        return $orders;
    }
}
