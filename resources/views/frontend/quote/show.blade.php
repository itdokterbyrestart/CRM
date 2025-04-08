@extends('layouts/fullLayoutMaster')

@section('title', 'Offerte ' . $quote->title)

@section('vendor-style')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<!-- modalForm -->
<div wire:ignore.self class="modal fade" data-backdrop="static" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalForm">Offerte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @livewire('frontend.quote.form')
            </div>
        </div>
    </div>
</div>

<div class="container-lg d-print-none">
	<div class="row">
		<div class="col-12">
			<div class="card mt-2">
				<div class="card-header">
					<h2 class="mb-0">Offerte status</h2>
				</div>
				<div class="card-body">
					@livewire('frontend.quote.show-status', ['quote_id' => $quote->id])
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h1>Offerte {{ lcfirst($quote->title) }} | {{ config('app.name') }}</h1>
				</div>
				<div class="card-body py-0">
					<p class="h4 font-weight-normal">
						{!! nl2br($quote->quote_text) !!}
					</p>

					<a href="{{ asset('images/logo/logo.png') }}" data-lightbox="logo" data-title="{{ config('app.name') }}">
						<img src="{{ asset('images/logo/logo.png') }}" height="115" alt="Logo" class="mb-2">
					</a>
					
					@foreach ($quote_products as $product)
						@if (count($product->media) > 0)
							<div class="alert alert-info p-1 mt-1 d-print-none" role="alert">
								<i class="fas fa-info-circle"></i>
								Klik op een afbeelding om hem groter te maken!
							</div>
							@break
						@endif
					@endforeach
					<div class="alert alert-info d-md-none p-1 mt-1 d-print-none" role="alert">
						<i class="fas fa-info-circle"></i>
						De tabel is horizontaal scrollbaar!
					</div>

					@if (!empty($package_image))
					<h2>Offerte pakketten</h2>
						<div class="w-100">
							<a href="{{ $package_image }}" data-lightbox="pakketten" data-title="Offerte pakketten">
								<img src="{{ asset($package_image) }}" alt="Huidige pakket afbeelding"  style="width: 100%; max-height: 70vh; object-fit: contain; object-position:left;">
							</a>
						</div>
					@endif
				</div>

				@foreach ($quote_products as $product)
					@php
						$mediaItems = $product->getMedia('product_images');
					@endphp

					<div class="card-body @if(!$loop->first) mt-1 @endif">
						<h2>Offerte {{ $product->name }} 
							@if (isset($product->highlight_text)) 
								@if ($product->highlight_text != '')
									<br><span class="badge bg-primary p-1">{{ $product->highlight_text }}</span>
								@endif
							@endif
						</h2>
						
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									@if ($mediaItems->count() > 0 && $product->show_product_images == true)
										<th width="@if ($quote->show_amount_and_total == true) 40% @else 50% @endif">Afbeelding</th>
									@endif
									<th width="@if ($quote->show_amount_and_total == true) 30% @else 40% @endif">Naam</th>
									<th width="@if ($quote->show_amount_and_total == true) 10% @else 10% @endif">Prijs</th>
									@if ($quote->show_amount_and_total == true)
										<th width="10%">Aantal</th>
										<th width="10%">Totaal</th>
									@endif
								</tr>
							</thead>
							<tbody>
								<tr class="table-primary">
									@if ($mediaItems->count() > 0 && $product->show_product_images == true)
										<td>
											<div class="row">
												@foreach ($mediaItems as $media)
													<div class="col-12 @if ($mediaItems->count() > 1) col-md-6 @endif @if ($mediaItems->count() > 2) col-lg-4 @endif  mb-1">
														<a href="{{ $media->getUrl() }}" data-lightbox="product_afbeeldingen_{{ $product->name }}" data-title="{{ $product->name }}">
															<img src="{{ $media->getUrl() }}" alt="Afbeelding {{ $loop->index }} - {{ $product->title }}" style="width: 100%; height: 100%; max-heigth: 350px; object-fit: cover;">
														</a>
													</div>											
												@endforeach
											</div>
										</td>
									@endif
									<td><p class="h4 font-weight-normal">{{ $product->name }}</p></td>
									<td><p class="h5 font-weight-normal">
										@if ($product->use_discount_prices == 1) 
											<s style="color: #8f8f8f;">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}</s> <span class="text-danger">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->discount_price_customer_excluding_tax : $product->discount_price_customer_including_tax), 2, '.', '') }}</span>
										@else
											€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}
										@endif
									</p></td>
									@if ($quote->show_amount_and_total == true)
										<td><p class="h5 font-weight-normal">{{ number_format($product->amount, 2, '.', '') }}</p></td>
										<td><p class="h5 font-weight-normal">
											@if ($product->use_discount_prices == 1) 
												<s style="color: #8f8f8f;">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->total_price_customer_excluding_tax : $product->total_price_customer_including_tax), 2, '.', '') }}</s> <span class="text-danger">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->total_discount_price_customer_excluding_tax : $product->total_discount_price_customer_including_tax), 2, '.', '') }}</span>
											@else
												€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->total_price_customer_excluding_tax : $product->total_price_customer_including_tax), 2, '.', '') }}
											@endif
										</p></td>
									@endif
								</tr>
								@if ($product->description !== null)
									@php
										$description_array = preg_split("/\n|\r\n/", $product->description);
										if ($description_array[0] === '') {
											$description_array = [];
										}
									@endphp
									@if (count($description_array) > 0)
										@foreach ($description_array as $row)
											<tr>
												<td colspan="100%" class="@if (!$loop->last) py-0 @else pt-0 @endif">
													<span class="font-italic h4 font-weight-normal">* {!! $row !!}</span>
												</td>
											</tr>
										@endforeach
									@endif
								@endif
							</tbody>
						</table>
					</div>
				@endforeach

				@foreach ($quote_product_groups as $product_group)
					@if (count($product_group->products) > 0)
						<div class="card-body @if(!$loop->first) mt-1 @endif">
							<h2>Offerte {{ $product_group->name }}</h2>
							@if (isset($product_group->description) && $product_group->description_before_products == 1)
								@if (!is_null($product_group->description) AND $product_group->description != '')
									<p class="h4 font-weight-normal">{!! nl2br($product_group->description) !!}</p>							
								@endif
							@endif
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										@php
											$showImages = false;
											foreach ($product_group->products as $product) {
												$mediaItems = $product->getMedia('product_images');
												if ($mediaItems->count() > 0) {
													$showImages = true;
													break;
												}
											}
										@endphp
										@if ($showImages && $quote->show_product_group_images == true)
											<th width="@if ($quote->show_amount_and_total == true) 40% @else 50% @endif">Afbeelding</th>
										@endif
										<th width="@if ($quote->show_amount_and_total == true) 30% @else 40% @endif">Naam</th>
										<th width="@if ($quote->show_amount_and_total == true) 10% @else 10% @endif">Prijs</th>
										@if ($quote->show_amount_and_total == true)
											<th width="10%">Aantal</th>
											<th width="10%">Totaal</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach ($product_group->products as $product)
									@php
										$mediaItems = $product->getMedia('product_images');
									@endphp
										<tr class="table-primary">
											@if ($showImages && $quote->show_product_group_images == true)
											<td>
												<div class="row">
													@forelse ($mediaItems as $media)
														<div class="col-12 @if ($mediaItems->count() > 1) col-md-6 @endif @if ($mediaItems->count() > 2) col-lg-4 @endif  mb-1">
															<a href="{{ $media->getUrl() }}" data-lightbox="product_afbeeldingen_{{ $product->name }}" data-title="{{ $product->name }}">
																<img src="{{ $media->getUrl() }}" alt="Afbeelding {{ $loop->index }} - {{ $product->title }}" style="width: 100%; height: 100%; max-heigth: 350px; object-fit: cover;">
															</a>
														</div>
													@empty
														<p>Geen afbeelding</p>
													@endforelse
												</div>
											</td>
											@endif
											<td><p class="h4 font-weight-normal">{{ $product->name }}</p></td>
											<td><p class="h5 font-weight-normal">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}</p></td>
											@if ($quote->show_amount_and_total == true)
												<td><p class="h5 font-weight-normal">{{ number_format(1, 2, '.','.') }}</p></td>
												<td><p class="h5 font-weight-normal">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}</p></td>
											@endif
										</tr>
										@if ($product->description !== null)
											@php
												$description_array = preg_split("/\n|\r\n/", $product->description);
												if ($description_array[0] === '') {
													$description_array = [];
												}
											@endphp
											@if (count($description_array) > 0)
												@foreach ($description_array as $row)
													<tr>
														<td colspan="100%" class="@if (!$loop->last) py-0 @else pt-0 @endif">
															<span class="font-italic h4 font-weight-normal">* {!! $row !!}</span>
														</td>
													</tr>
												@endforeach
											@endif
										@endif
									@endforeach
								</tbody>
							</table>
						</div>
						@if (isset($product_group->description) && $product_group->description_before_products == 0)
							@if (!is_null($product_group->description) AND $product_group->description != '')
								<div class="card-body pb-0 pt-1">
									<p class="h4 font-weight-normal">{!! nl2br($product_group->description) !!}</p>
								</div>								
							@endif
						@endif
					@endif
				@endforeach
			</div>
		</div>
	</div>
</div>

<div class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="card mt-2">
				<div class="card-header">
					<h2 class="mb-0">Voorwaarden</h2>
				</div>
				<div class="card-body">
					<p>Alle prijzen zijn  {{ $quote->prices_exclude_tax == 0 ? 'inclusief' : 'exclusief' }} BTW, tenzij anders aangegeven.<br>Op alle diensten zijn de <a href="{{ $terms_and_services_link }}" target="_blank">algemene voorwaarden</a> van toepassing.<br>Afbeeldingen en kleuren kunnen afwijken van de werkelijkheid. @if ($deposit_enabled == 1) <br>De facturering vindt plaats {{ $deposit_percentage_amount }}% vooraf en {{ 100 - (int)$deposit_percentage_amount }}% achteraf. @endif</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('vendor-script')
	<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		lightbox.option({
			'alwaysShowNavOnTouchDevices': true,
			'disableScrolling': true,
			'wrapAround': true,
			'imageFadeDuration': 300,
			'fadeDuration': 300,
		})
	</script>
@endsection

@section('page-script')
{{-- Bootstrap edit modal --}}
<script>
    window.addEventListener('closeModal', event => {
        $("#modalForm").modal('hide');
    })
    window.addEventListener('openModal', event => {
        $("#modalForm").modal('show');
    })
    $(document).ready(function(){
        // This event is triggered when the modal is hidden
        $("#modalForm").on('hidden.bs.modal', function(){
            livewire.emit('forcedCloseModal');
        });
    });
</script>
@endsection
