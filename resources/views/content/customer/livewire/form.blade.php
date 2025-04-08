<div class="card">
	<div class="card-body">
		<form wire:submit="store" class="my-1">
			<div class="row justify-content-between align-items-start mb-2">
				<div class="col-auto">
					<a href="{{ route('customer.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
						@if ($edit == 1) Annuleren @else Terug @endif
					</a>
				</div>
				@if ($edit == 1)
					<div class="col-auto">
						<button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
							<span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Opslaan
						</button>
					</div>
				@else
					<div class="col-auto">
						<a class="btn btn-primary" href="{{ route('customer.edit', $modelId) }}" tabindex="0">
							Aanpassen
						</a>
					</div>
				@endif
			</div>

			
			<div class="row">
				@if ($edit == 0)
					<div class="col-12">
						<label for="id" class="col-form-label col-form-label-md">ID</label>
						<input wire:model.blur="modelId" type="integer" class="form-control form-control-md" tabindex="0" disabled>
					</div>
				@endif

				<div class="col-12 col-md-5">
					<label for="name" class="col-form-label col-form-label-md">Naam *</label>
					<input wire:model.blur="name" type="text" class="form-control form-control-md @error('name') is-invalid @enderror" id="name" required autofocus tabindex="0" @if ($edit == 0) disabled @endif>
					@error('name')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-5">
					<label for="company" class="col-form-label col-form-label-md">Bedrijf</label>
					<input wire:model.blur="company" type="text" class="form-control form-control-md @error('company') is-invalid @enderror" id="company" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('company')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-2">
					<label for="discount" class="col-form-label col-form-label-md">Korting</label>
					<input wire:model.blur="discount" type="text" class="form-control form-control-md @error('discount') is-invalid @enderror" id="discount" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('discount')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<hr>

				<div class="col-12 col-lg-4">
					<label for="email" class="col-form-label col-form-label-md">Email</label>
					<input wire:model.blur="email" type="email" class="form-control form-control-md @error('email') is-invalid @enderror" id="email" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<label for="email2" class="col-form-label col-form-label-md">Email 2</label>
					<input wire:model.blur="email2" type="email2" class="form-control form-control-md @error('email2') is-invalid @enderror" id="email2" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('email2')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<label for="email3" class="col-form-label col-form-label-md">Email 3</label>
					<input wire:model.blur="email3" type="email3" class="form-control form-control-md @error('email3') is-invalid @enderror" id="email3" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('email3')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<hr>

				<div class="col-12 col-lg-4">
					<label for="phone" class="col-form-label col-form-label-md">Telefoonnummer</label>
					<input wire:model.blur="phone" type="phone" class="form-control form-control-md @error('phone') is-invalid @enderror" id="phone" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('phone')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<label for="phone2" class="col-form-label col-form-label-md">Telefoonnummer 2</label>
					<input wire:model.blur="phone2" type="phone2" class="form-control form-control-md @error('phone2') is-invalid @enderror" id="phone2" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('phone2')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-6 col-lg-4">
					<label for="phone3" class="col-form-label col-form-label-md">Telefoonnummer 3</label>
					<input wire:model.blur="phone3" type="phone3" class="form-control form-control-md @error('phone3') is-invalid @enderror" id="phone3" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('phone3')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<hr>

				<div class="col-9 col-md-8">
					<label for="street" class="col-form-label col-form-label-md">Straat</label>
					<input wire:model.blur="street" type="text" class="form-control form-control-md @error('street') is-invalid @enderror" id="street" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('street')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-3 col-md-4">
					<label for="number" class="col-form-label col-form-label-md">Nummer</label>
					<input wire:model.blur="number" type="text" class="form-control form-control-md @error('number') is-invalid @enderror" id="number" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('number')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-6">
					<label for="postal_code" class="col-form-label col-form-label-md">Postcode</label>
					<input wire:model.blur="postal_code" type="text" class="form-control form-control-md @error('postal_code') is-invalid @enderror" id="postal_code" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('postal_code')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 col-md-6">
					<label for="place_name" class="col-form-label col-form-label-md">Plaatsnaam</label>
					<input wire:model.blur="place_name" type="text" class="form-control form-control-md @error('place_name') is-invalid @enderror" id="place_name" tabindex="0" @if ($edit == 0) disabled @endif>
					@error('place_name')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<hr>

				<div class="col-12">
					<label for="comment" class="col-form-label col-form-label-md">Commentaar</label>
					<textarea wire:model.blur="comment" type="text" class="form-control form-control-md @error('comment') is-invalid @enderror" id="comment" tabindex="0" rows="2" @if ($edit == 0) disabled @endif>
					</textarea>
					@error('comment')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="col-12 mt-1">
					<div class="form-check">
						<input wire:model.blur="generated" type="checkbox" id="generated" class="form-check-input" @if ($edit == 0) disabled @endif>
						<label for="generated" class="form-check-label">Gegenereerd</label>
						@error('generated')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>

				<div class="col-12">
					<hr>
					<h3>Services</h3>
					<div class="mb-1">
						@forelse ($customerservices as $index => $customerservice)
							<div class="form-row">
								<div class="col-12 col-md-4">
									<label for="service_id.{{ $index }}" class="col-form-label col-form-label-md">Service</label>
									@if ($edit == 1)
										<div class="input-group">
									@endif
									<select wire:model.blur="service_id.{{ $index }}" class="form-control form-control-md @error('service_id.' . $index) is-invalid @enderror" id="service_id.{{ $index }}" @if ($edit == 0) disabled @endif>
										<option value="" disabled hidden>Kies een Service</option>
										@foreach ($services as $service)
												<option value="{{ $service->id }}">{{ $service->name }}</option>
										@endforeach
									</select>

									@error('service_id.' . $index)
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
									@if ($edit == 1)
										</div>
									@endif
								</div>

								<div class="col-12 col-md-4">
									<div class="form-group">
										<label for="service_month.{{ $index }}" class="col-form-label col-form-label-md">Maand</label>
										@if ($edit == 1)
											<div class="input-group">
										@endif
										<select wire:model.blur="service_month.{{ $index }}" id="service_month.{{ $index }}" class="form-control @error('service_month.' . $index) is-invalid @enderror" tabindex="0"  @if ($edit == 0) disabled @endif>
											<option value="">Kies een maand</option>
											<option value="1">Januari</option>
											<option value="2">Februari</option>
											<option value="3">Maart</option>
											<option value="4">April</option>
											<option value="5">Mei</option>
											<option value="6">Juni</option>
											<option value="7">Juli</option>
											<option value="8">Augustus</option>
											<option value="9">September</option>
											<option value="10">Oktober</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
										@error('service_month.' . $index)
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
										@if ($edit == 1)
											</div>
										@endif
									</div>
								</div>

								<div class="col-12 col-md-4">
									<label for="service_description.{{ $index }}" class="col-form-label col-form-label-md">Beschrijving</label>
									@if ($edit == 1)
										<div class="input-group">
									@endif
									<input wire:model.blur="service_description.{{ $index }}" type="text" class="form-control form-control-md @error('service_description.' . $index) is-invalid @enderror" id="service_description.{{ $index }}" tabindex="0" @if ($edit == 0) disabled @endif>
									@error('service_description.' . $index)
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
									@if ($edit == 1)
										</div>
									@endif
								</div>

								@if ($edit == 0)
									<div class="col-12 col-sm-6">
										<label for="service_updated_at.{{ $index }}" class="col-form-label col-form-label-md">Aangepast op</label>
										<input wire:model.blur="service_updated_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
									</div>
								@endif

								@if ($edit == 0)
									<div class="col-12 col-sm-6">
										<label for="service_created_at.{{ $index }}" class="col-form-label col-form-label-md">Gemaakt op</label>
										<input wire:model.blur="service_created_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
									</div>
								@endif


								@if ($edit == 1)
									<div class="col-12 my-1">
										@if ($loop->last)
											<div wire:click="addService" class="btn btn-primary" tabindex="0">
												<span wire:target="addService" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
												+ Service toevoegen
											</div>
										@endif
										<div wire:click="removeService({{ $index }})" class="btn btn-danger" tabindex="0">
											<span wire:target="removeService" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
											- Service verwijderen
										</div>
									</div>
								@endif
							</div>
							@if (!$loop->last)
								<hr>
							@endif
						@empty
							<p>Er zijn geen services toegevoegd</p>
							@if ($edit == 1 AND count($customerservices) === 0)
								<div wire:click="addService"class="btn btn-primary" tabindex="0">
									<span wire:target="addService" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
									+ Service toevoegen
								</div>
							@endif
						@endforelse
					</div>
				</div>

				<div class="col-12">
					<hr>
					<h3>Uren en producten</h3>
					<div class="mb-1">
						@forelse ($order_products_and_hours as $index => $order_product_and_hour)
							@if ($loop->iteration == 35)
								@break
							@endif
							<div class="form-row">
								<div class="col-12 col-sm-6 col-lg-2">
									<label for="order_product_and_hour_type.{{ $index }}" class="col-form-label col-form-label-md">Type</label>
									<input value="{{ $order_product_and_hour['type'] }}" type="text" class="form-control form-control-md" id="order_product_and_hour_type.{{ $index }}" tabindex="0" disabled>
								</div>
								
								<div class="col-12 col-sm-6 col-lg-2">
									<label for="order_product_and_hour_date.{{ $index }}" class="col-form-label col-form-label-md">Datum</label>
									<input value="{{ Carbon\Carbon::parse($order_product_and_hour['date'])->format('d-m-Y') }}" type="text" class="form-control form-control-md" id="order_product_and_hour_date.{{ $index }}" tabindex="0" disabled>
								</div>
								
								<div class="col-12 col-sm-6 col-lg-4">
									<label for="order_product_and_hour_name.{{ $index }}" class="col-form-label col-form-label-md">Naam</label>
									<input value="{{ $order_product_and_hour['name'] }}" type="text" class="form-control form-control-md" id="order_product_and_hour_name.{{ $index }}" tabindex="0" disabled>
								</div>

								<div class="col-12 col-sm-3 col-lg-2">
									<label for="order_product_and_hour_amount.{{ $index }}" class="col-form-label col-form-label-md">Aantal</label>
									<input value="{{ $order_product_and_hour['amount'] }}" type="number" step="any" class="form-control form-control-md" id="order_product_and_hour_amount.{{ $index }}" tabindex="0" disabled>
								</div>

								<div class="col-12 col-sm-3 col-lg-2">
									<label for="order_product_and_hour_name_price.{{ $index }}" class="col-form-label col-form-label-md">Prijs</label>
									<input value="{{ $order_product_and_hour['total_price_customer_including_tax'] }}" type="number" step="any" class="form-control form-control-md" id="order_product_and_hour_price.{{ $index }}" tabindex="0" disabled>
								</div>

								@if ($order_product_and_hour['description'] != (null OR ''))
									<div class="col-12">
										<label for="order_product_and_hour_description.{{ $index }}" class="col-form-label col-form-label-md">Beschrijving</label>
										<textarea type="text" class="form-control form-control-md" id="order_product_and_hour_description.{{ $index }}" tabindex="0" rows="1" disabled>{{ $order_product_and_hour['description'] }}</textarea>
									</div>
								@endif
							</div>
							<hr>
						@empty
							<p>Er zijn geen producten en uren toegevoegd</p>
						@endforelse
					</div>
				</div>

				<hr>

				@if ($edit == 0)
					<div class="col-12">
						<label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
						<input wire:model.blur="updated_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
					</div>
				@endif

				@if ($edit == 0)
					<div class="col-12">
						<label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
						<input wire:model.blur="created_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
					</div>
				@endif

				@if ($edit == 1)
					<div class="col-12 mt-1">
						<a href="{{ route('customer.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
							Annuleren
						</a>
						<button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
							<span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Opslaan
						</button>
					</div>
				@endif
			</div>
		</form>
	</div>
</div>
