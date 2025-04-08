<div class="container-lg">
	<div class="card">
		<div class="card-body">
			<div class="d-flex flex-column flex-md-row justify-content-between mt-4">
				<div class="order-2 order-md-1 mb-2 mb-md-0 align-self-start align-self-md-center">
					<div class="d-flex flex-row">
						<h4 class="mr-2">Aan:</h4>
						<h4>
							@if (strlen($customer->company) > 0 )
								{{ $customer->company }}<br>
							@endif
							{{ $customer->name }}<br>
							{{ $customer->street }}&nbsp;{{ $customer->number }}<br>
							{{ $customer->postal_code }}&nbsp;&nbsp;{{ $customer->place_name }}
						</h4>
					</div>
				</div>
				<div class="offset-md-2 order-1 order-md-2 mb-3 mb-md-0">
					<div class="d-flex flex-column">
						<img src="{{ asset('images/logo/logo.png') }}" height="115" class="align-self-start">
						<h5 class="mb-1">
							<br>
							<b>{{ config('app.name') }}</b><br>
							{!! $business_address !!}
						</h5>
					</div>
					<div class="d-flex flex-row">
						<h5 class="mr-1">
							KVK-nr:<br>
							BTW-id:<br>
							Bank:<br>
							IBAN:<br>
							Tel:<br>
							E-mail:
						</h5>
						<h5>
							{{ $business_kvk }}<br>
							{{ $business_VAT_number }}<br>
							{{ $business_bank }}<br>
							{!! $business_iban !!}<br>
							<a href="tel:{!! $business_phone !!}">{!! $business_phone !!}</a><br>
							<a href="mailto:{{ $business_email }}?subject=Vraag over factuur {{ $invoice->invoice_number }}">{{ $business_email }}</a>
						</h5>
					</div>
				</div>
			</div>
			
			@if ($invoice->extra_invoice_data)
				<h4 class="mb-3">{!! nl2br($invoice->extra_invoice_data) !!}</h4>
			@endif
			
			<div class="d-flex flex-row flex-wrap mb-2">
				<h1 class="mb-0">
					Factuur
				</h1>
			</div>

			<div class="row col justify-content-between mb-2">
				<style scoped>
					.bor {
						border-left: solid 5px {{ $email_template_color }};
					}
				</style>
				<div class="col-12 col-sm-4 bor mb-1">
					<div class="ml-1">
						<h4>Factuurnummer</h4>
						<h5><b>{{ $invoice->invoice_number }}</b></h5>
					</div>
				</div>
				<div class="col-12 col-sm-4 bor mb-1">
					<div class="ml-1">
						<h4>Factuurdatum</h4>
						<h5><b>{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</b></h5>
					</div>
				</div>
				<div class="col-12 col-sm-4 bor mb-1">
					<div class="ml-1">
						<h4>Vervaldatum</h4>
						<h5><b>{{ Carbon\Carbon::parse($invoice->expiration_date)->format('d-m-Y') }}</b></h5>
					</div>
				</div>
			</div>

			<div class="alert alert-info d-md-none p-1 d-print-none" role="alert">
				<i class="fas fa-info-circle"></i>
				De tabel is horizontaal scrollbaar!
			</div>

			<div class="table-responsive mb-3">
				<table class="table table-striped table-hover">
					<thead>
						<tr class="text-primary">
							<th class="pl-1 pr-0"><h5 class="text-primary font-weight-sm-bolder">Datum</h5></th>
							<th class="pl-1 pr-0"><h5 class="text-primary font-weight-sm-bolder">Omschrijving</h5></th>
							<th class="pl-1 pr-0"><h5 class="text-primary font-weight-sm-bolder">Prijs</h5></th>
							<th class="pl-1 pr-0"><h5 class="text-primary font-weight-sm-bolder">BTW</h5></th>
							<th class="pl-1 pr-0"><h5 class="text-primary font-weight-sm-bolder">Aantal</h5></th>
							<th class="pl-1 pr-0"><h5 class="text-primary font-weight-sm-bolder">Subtotaal</h5></th>
						</tr>
					</thead>
					<tbody>							
						@foreach ($products as $product)
						<tr>
							<td class="pl-1 pr-0" nowrap="nowrap">{{ Carbon\Carbon::parse($product->created_at)->format('d-m-Y') }}</td>
							<td class="pl-1 pr-0">{{ $product->name }}@if ($product->pivot->comment)<br>{{ $product->pivot->comment }}@endif</td>
							<td class="pl-1 pr-0" nowrap="nowrap">€&nbsp;{{ number_format(($invoice->calculation_method_excluding_tax == false ? $product->price_customer_including_tax : $product->price_customer_excluding_tax),2,',','') }}</td>
							<td class="pl-1 pr-0" nowrap="nowrap">{{ $product->tax_percentage }}%</td>
							<td class="pl-1 pr-0" nowrap="nowrap">{{ number_format($product->amount,2,',','') }}</td>
							<td class="pl-1 pr-0" nowrap="nowrap">€&nbsp;{{ number_format(($invoice->calculation_method_excluding_tax == false ? $product->total_price_customer_including_tax : $product->revenue),2,',','') }}</td>
						</tr>
						@endforeach
						@foreach ($hours as $hour)
							<tr>
								<td class="pl-1 pr-0" nowrap="nowrap">{{ Carbon\Carbon::parse($hour->date)->format('d-m-Y') }}</td>
								<td class="pl-1 pr-0">{{ $hour->pivot->comment . ' (uurbasis)' }}</td>
								<td class="pl-1 pr-0" nowrap="nowrap">€&nbsp;{{ number_format(($invoice->calculation_method_excluding_tax == false ? $hour->price_customer_including_tax : $hour->price_customer_excluding_tax),2,',','') }}</td>
								<td class="pl-1 pr-0" nowrap="nowrap">{{ $hour->tax_percentage }}%</td>
								<td class="pl-1 pr-0" nowrap="nowrap">{{ number_format($hour->amount,2,',','') }}</td>
								<td class="pl-1 pr-0" nowrap="nowrap">€&nbsp;{{ number_format(($invoice->calculation_method_excluding_tax == false ? $hour->amount_revenue_including_tax : $hour->amount_revenue_excluding_tax),2,',','') }}</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td class="pl-1 pr-0" colspan="5"><h4 class="mb-0 text-right"><b>Totaal te betalen</b></h4></td>
							<td class="pl-1 pr-0"><h4 class="mb-0"><b>€&nbsp;{{ $total_price_customer_including_tax }}</b></h4></td>
						</tr>
						<tr>
							<td class="pl-1 pr-0" colspan="5"><h5 class="mb-0 text-right">Totaal excl. BTW</h5></td>
							<td class="pl-1 pr-0"><h5 class="mb-0">€&nbsp;{{ $total_price_customer_excluding_tax }}</h5></td>
						</tr>
						<tr>
							<td class="pl-1 pr-0" colspan="5"><h5 class="mb-0 text-right">Totaal BTW</h5></td>
							<td class="pl-1 pr-0"><h5 class="mb-0">€&nbsp;{{ $total_tax_amount }}</h5></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<h4>
				Opmerkingen & Voorwaarden
			</h4>
			<h5 class="font-weight-normal">
				{!! nl2br($invoice_comments_text) !!}
			</h5>
		</div>
	</div>
</div>