@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('content')

<div class="row">
	{{-- date input --}}
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Data selectie</h4>
			</div>
			<div class="card-body">
				<form action="{{ route('dashboard.date_change') }}" method="post">
					@csrf
					<div class="form-row">
						<div class="form-group col-6">
							<label for="start_date_input">Start datum</label>
							<input type="date" name="start_date_input" id="start_date_input" class="form-control @error('start_date_input') is-invalid @enderror" value="{{ old('start_date_input', $start_date_input) }}">
							@error('start_date_input')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="form-group col-6">
							<label for="end_date_input">Eind datum</label>
							<input type="date" name="end_date_input" id="start_date" class="form-control @error('end_date_input') is-invalid @enderror" value="{{ old('end_date_input', $end_date_input) }}">
							@error('end_date_input')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
					<button type="submit" class="btn btn-primary">Update</button>
				</form>
			</div>
		</div>
	</div>
	{{-- end date input --}}

	{{-- total revenue --}}
	<div class="col-12 col-xl-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Opbrengst</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<h5>Producten</h5>
						<h4 class="font-weight-light">€{{ number_format($total_products_revenue, 2, ',', '.') }}</h4>
					</div>
					<div class="col-4">
						<h5>Uren</h5>
						<h4 class="font-weight-light">€{{ number_format($total_hours_revenue, 2, ',', '.') }}</h4>
					</div>
					<div class="col-4">
						<h5>Totaal</h5>
						<h4 class="font-weight-light">€{{ number_format($total_revenue, 2, ',', '.') }}</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- end total revenue --}}

	{{-- total cost --}}
	<div class="col-12 col-xl-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Kosten</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<h5>Producten</h5>
						<h4 class="font-weight-light">€{{ number_format($total_products_costs, 2, ',', '.') }}</h4>
					</div>
					<div class="col-4">
						<h5>Uren Joris</h5>
						<h4 class="font-weight-light">€{{ number_format($total_cost_hours_joris, 2, ',', '.') }}</h4>
					</div>
					<div class="col-4">
						<h5>Totaal</h5>
						<h4 class="font-weight-light">€{{ number_format($total_cost, 2, ',', '.') }}</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- end total cost --}}

	{{-- total profit --}}
	<div class="col-12 col-xl-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Winst</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<h5>Producten</h5>
						<h4 class="font-weight-light">€{{ number_format($total_products_profit, 2, ',', '.') }}</h4>
					</div>
					<div class="col-4">
						<h5>Uren</h5>
						<h4 class="font-weight-light">€{{ number_format($total_hours_profit, 2, ',', '.') }}</h4>
					</div>
					<div class="col-4">
						<h5>Totaal</h5>
						<h4 class="font-weight-light">€{{ number_format($total_profit, 2, ',', '.') }}</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- end total profit --}}

	{{-- hour amount sum --}}
	<div class="col-12 col-sm-6 col-md-3">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Uren</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($order_hour_amount_sum, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end hour amount sum --}}

	{{-- product amount count --}}
	<div class="col-12 col-sm-6 col-md-3">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Producten verkocht</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($order_product_count, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end hour amount count --}}

	{{-- order amount count --}}
	<div class="col-12 col-sm-6 col-md-3">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Opdrachten</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($order_amount_count, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end order amount count --}}

	{{-- unique customer count --}}
	<div class="col-12 col-sm-6 col-md-3">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Unieke klanten</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($unique_customer_count, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end unique customer count --}}

	{{-- mean profit per hour --}}
	<div class="col-12 col-sm-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Gemiddelde winst per uur</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">€{{ number_format($mean_profit_per_hour, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end mean profit per hour --}}

	{{-- mean product sold margin --}}
	<div class="col-12 col-sm-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Marge producten verkocht</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($product_margin_sold_products, 2, ',', '.') }}%</h4>
			</div>
		</div>
	</div>
	{{-- end mean product sold margin --}}

	{{-- mean product amount per order --}}
	<div class="col-12 col-sm-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Gemiddeld aantal producten per opdracht</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($mean_order_products_count_per_order, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end product amount per order --}}

	{{-- mean hour amount per order --}}
	<div class="col-12 col-sm-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Gemiddeld aantal uren per opdracht</h4>
			</div>
			<div class="card-body">
				<h4 class="font-weight-light">{{ number_format($mean_order_hours_amount_per_order, 2, ',', '.') }}</h4>
			</div>
		</div>
	</div>
	{{-- end hour amount per order --}}
</div>

@endsection
