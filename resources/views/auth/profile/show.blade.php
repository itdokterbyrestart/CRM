@extends('layouts/contentLayoutMaster')

@section('title', 'Profiel')

@section('vendor-style')
@endsection
@section('page-style')
	<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
<section class="app-user-view">
	<!-- User Card & uren per maand -->
	<div class="row">
		<!-- User Card starts-->
		<div class="col-12">
			<div class="card user-card">
				<div class="card-body">
					<div class="row">
						<div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
							<div class="user-avatar-section">
								<div class="d-flex justify-content-start">
									<img
										class="img-fluid rounded"
										src="{{ asset('images/logo/logo.png') }}"
										height="104"
										width="104"
										alt="User avatar"
									/>
									<div class="d-flex flex-column ml-1">
										<div class="user-info mb-1">
											<h4 class="mb-0">{{ $user->name }}</h4>
											<span class="card-text">{{ $user->email }}</span>
										</div>
										<div class="d-flex flex-wrap">
											<a href="{{ route('profile.edit') }}" class="btn btn-primary">Aanpassen</a>
										</div>
									</div>
								</div>
							</div>
							<div class="d-flex align-items-center user-total-numbers">
								<div class="d-flex align-items-center mr-2">
									<div class="color-box bg-light-primary">
										<i class="fas fa-clock text-primary"></i>
									</div>
									<div class="ml-1">
										<h5 class="mb-0">{{ $total_hours_made }}</h5>
										<small>Uren totaal</small>
									</div>
								</div>
								{{-- <div class="d-flex align-items-center">
									<div class="color-box bg-light-success">
										<i class="fas fa-chart-line text-success"></i>
									</div>
									<div class="ml-1">
										<h5 class="mb-0">€{{ $total_hours_made * 20 }}</h5>
										<small>Salaris totaal</small>
									</div>
								</div> --}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /User Card Ends-->

		@livewire('auth.profile.show')

		<!-- Uren per maand-->
		<div class="col-12 col-md-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Uren per maand (tot 1 jaar terug)</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-1">
						<thead>
							<tr>
								<th>Maand</th>
								<th>Uren</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($hours as $hour)
								<tr>
									<td>{{ __('dates.' . date("F", mktime(null, null, null, $hour->month))) }}</td>
									<td>{{ $hour->amount_sum }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="100%">
										<span class="font-weight-bold">Er is geen data gevonden</span>
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					{!! $hoursPerMonthChart->renderHtml() !!}
				</div>
			</div>
		</div>
		<!-- /Uren per maand-->

		<!-- Producten per maand-->
		<div class="col-12 col-md-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Waarde verkochte producten incl. BTW per maand (tot 1 jaar terug)</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-1">
						<thead>
							<tr>
								<th>Maand</th>
								<th>Aantal</th>
								<th>Inkoopwaarde</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($products as $product)
								<tr>
									<td>{{ __('dates.' . date("F", mktime(null, null, null, $product->month))) }}</td>
									<td>{{ $product->purchase_price_excluding_tax_count }}</td>
									<td>€{{ $product->purchase_price_excluding_tax_sum }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="100%">
										<span class="font-weight-bold">Er is geen data gevonden</span>
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					{!! $productsPerMonthChart->renderHtml() !!}
				</div>
			</div>
		</div>
		<!-- /Uren per maand-->

		<!-- Kilometers per maand-->
		<div class="col-12 col-md-6">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Kilometers per maand (tot 1 jaar terug)</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover mb-1">
						<thead>
							<tr>
								<th>Maand</th>
								<th>Kilometers</th>
								<th>Tijd (minuten)</th>
								<th>Vergoeding</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($kilometers as $kilometer)
								<tr>
									<td>{{ __('dates.' . date("F", mktime(null, null, null, $kilometer->month))) }}</td>
									<td>{{ $kilometer->kilometers_sum }}</td>
									<td>{{ $kilometer->time_minutes_sum }}</td>
									<td>€{{ number_format(($kilometer->kilometers_sum * 0.25) + ($kilometer->time_minutes_sum/60 * 20), 2, '.', '') }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="100%">
										<span class="font-weight-bold">Er is geen data gevonden</span>
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					{!! $kilometersPerMonthChart->renderHtml() !!}
				</div>
			</div>
		</div>
		<!-- /Kilometers per maand-->
	</div>
	<!-- User Card & uren per maand Ends -->
</section>
@endsection

@section('vendor-script')
@endsection
@section('page-script')
	<script src="{{ asset(mix('js/scripts/pages/app-user-view.js')) }}"></script>
	<script src="{{ asset(mix('vendors/js/charts/chart.min.js')) }}"></script>
	{!! $hoursPerMonthChart->renderJs() !!}
	{!! $productsPerMonthChart->renderJs() !!}
	{!! $kilometersPerMonthChart->renderJs() !!}
@endsection
