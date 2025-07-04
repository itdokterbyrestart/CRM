<?php

namespace App\Http\Controllers\Auth;

//Requests
use App\Http\Requests\Auth\{
    ProfileStoreRequest,
};

use App\Models\{
    OrderHour,
    OrderProduct,
};

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Hash;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;


class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['name' => "Profiel"]
        ];

        $total_hours_made = OrderHour::where('user_id', $user->id)->sum('amount');

        $hours = OrderHour::query()
            ->selectRaw('SUM(amount) as amount_sum, MONTH(date) as month')
            ->where('user_id', $user->id)
            ->whereBetween('date',[Carbon::now()->startOfMonth()->subYears(1),Carbon::now()->startOfMonth()->subSeconds(1)])
            ->orderBy('month')
            ->groupByRaw('month, YEAR(date)')
            ->get();

        $products = OrderProduct::query()
            ->selectRaw('ROUND(SUM((purchase_price_including_tax)), 2) as purchase_price_including_tax_sum, count(purchase_price_including_tax) as purchase_price_including_tax_count, MONTH(created_at) as month')
            ->where('user_id', $user->id)
            ->whereBetween('created_at',[Carbon::now()->startOfMonth()->subYears(1),Carbon::now()->startOfMonth()->subSeconds(1)])
            ->orderBy('month')
            ->groupByRaw('month, YEAR(created_at)')
            ->get();

        $kilometers = OrderHour::query()
            ->selectRaw('SUM(kilometers) as kilometers_sum, SUM(time_minutes) as time_minutes_sum, MONTH(date) as month')
            ->where('user_id', $user->id)
            ->whereBetween('date',[Carbon::now()->startOfMonth()->subYears(1),Carbon::now()->startOfMonth()->subSeconds(1)])
            ->orderBy('month')
            ->groupByRaw('month, YEAR(date)')
            ->get();

        $hoursPerMonthChart_options = [
            'chart_title' => 'Gemaakte uren',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\OrderHour',
            'group_by_field' => 'date',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'date_format' => 'm-Y',
            'where_raw' => 'user_id = ' . $user->id,
            'filter_field' => 'date',
            'range_date_start' => Carbon::now()->startOfMonth()->subYears(1),
            'range_date_end' => Carbon::now()->startOfMonth()->subSeconds(1),
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_color' => config('app.primary_chart_color'),
        ];
        $productsPerMonthChart_options = [
            'chart_title' => 'Inkoopwaarde producten',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\OrderProduct',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'date_format' => 'm-Y',
            'where_raw' => 'user_id = ' . $user->id,
            'filter_field' => 'created_at',
            'range_date_start' => Carbon::now()->startOfMonth()->subYears(1),
            'range_date_end' => Carbon::now()->startOfMonth()->subSeconds(1),
            'aggregate_function' => 'sum',
            'aggregate_field' => 'revenue',
            'chart_color' => config('app.primary_chart_color'),
        ];
        $kilometersPerMonthChart_options = [
            'chart_title' => 'Gereden kilometers',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\OrderHour',
            'group_by_field' => 'date',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'date_format' => 'm-Y',
            'where_raw' => 'user_id = ' . $user->id,
            'filter_field' => 'date',
            'range_date_start' => Carbon::now()->startOfMonth()->subYears(1),
            'range_date_end' => Carbon::now()->startOfMonth()->subSeconds(1),
            'aggregate_function' => 'sum',
            'aggregate_field' => 'kilometers',
            'chart_color' => config('app.primary_chart_color'),
        ];
        $timeMinutesPerMonthChart_options = [
            'chart_title' => 'Tijd (minuten)',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\OrderHour',
            'group_by_field' => 'date',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'date_format' => 'm-Y',
            'where_raw' => 'user_id = ' . $user->id,
            'filter_field' => 'date',
            'range_date_start' => Carbon::now()->startOfMonth()->subYears(1),
            'range_date_end' => Carbon::now()->startOfMonth()->subSeconds(1),
            'aggregate_function' => 'sum',
            'aggregate_field' => 'time_minutes',
            'chart_color' => config('app.secondary_chart_color'),
        ];

        $hoursPerMonthChart = new LaravelChart($hoursPerMonthChart_options);
        $productsPerMonthChart = new LaravelChart($productsPerMonthChart_options);
        $kilometersPerMonthChart = new LaravelChart($kilometersPerMonthChart_options,$timeMinutesPerMonthChart_options);

        return view('auth.profile.show', compact('breadcrumbs','user','total_hours_made','hours', 'products','kilometers','hoursPerMonthChart','productsPerMonthChart','kilometersPerMonthChart'));
    }

    public function edit()
    {
        $user = Auth::user();

        $breadcrumbs = [
            ['link' => route('dashboard'), 'name' => "Dashboard"], ['link' => route('profile.show', $user), 'name' => "Profiel"], ['name' => "Aanpassen"]
        ];

        return view('auth.profile.edit', compact('breadcrumbs','user'));
    }

    public function update(ProfileStoreRequest $request)
    {
        $user = Auth::user();

        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
        ];

        if ($request['password'] != NULL) {
            $data['password'] = Hash::make($request['password']);
        }

        $user->update($data);

        Session::flash('success', [
            'title' => 'De wijzigingen zijn doorgevoerd',
            'text' => ''
        ]);
        return redirect(route('profile.show'));
    }
}
