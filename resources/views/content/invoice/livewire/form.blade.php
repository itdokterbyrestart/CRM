<div class="card">
	<div class="card-body">
        <form wire:submit="store" class="my-1">
            <div class="row justify-content-between align-items-start mb-2">
				<div class="col-auto">
					<a href="{{ route('invoice.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
						<a class="btn btn-primary" href="{{ route('invoice.edit', $modelId) }}" tabindex="0">
							Aanpassen
						</a>
					</div>
				@endif
			</div>
            

            <div class="form-row mb-1">

                @if ($edit === 0)
                    <div class="col-12 col-lg-6">
                        <label for="id" class="col-form-label col-form-label-md">ID</label>
                        <input wire:model.blur="modelId" type="integer" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif


                <div class="{{ $edit === 0 ? 'col-12 col-sm-6 col-lg-3' : 'col-6' }}">
                    <label for="invoice_number" class="col-form-label col-form-label-md">Factuurnummer</label>
                    <input wire:model.blur="invoice_number" type="number" class="form-control form-control-md @error('invoice_number') is-invalid @enderror" id="invoice_number" required tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('invoice_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="{{ $edit === 0 ? 'col-12 col-sm-6 col-lg-3' : 'col-6' }}">
                    <label for="status" class="col-form-label col-form-label-md">Status</label>
                    <select wire:model.live="status" class="form-control form-control-md @error('status') is-invalid @enderror" id="status" @if ($edit === 0) disabled @endif>
                        <option value="" disabled hidden>Kies een status</option>
                        @foreach ($invoicestatus as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($show_missing_invoice_numbers == true)
                    <div class="col-12">
                        <div class="alert alert-primary mt-2 p-2" role="alert">
                            @if (count($missing_invoice_numbers) > 0)
                                Ontbrekende factuurnummers: @foreach ($missing_invoice_numbers as $missing) {{ $missing }}@if(!$loop->last),@endif @endforeach
                            @else
                                Er zijn geen ontbrekende factuurnummers gevonden
                            @endif
                            <span class="float-right">
                                <i class="fas fa-times" wire:click="$toggle('show_missing_invoice_numbers')"></i>
                            </span>
                        </div>
                    </div>
                @endif

                <div class="col-12 col-sm-4">
                    <label for="total_price_customer_excluding_tax" class="col-form-label col-form-label-md">Bedrag excl. BTW</label>
                    <input wire:model.blur="total_price_customer_excluding_tax" type="text" class="form-control form-control-md" id="total_price_customer_excluding_tax" tabindex="0" disabled>
                </div>

                <div class="col-12 col-sm-4">
                    <label for="total_tax_amount" class="col-form-label col-form-label-md">BTW bedrag</label>
                    <input wire:model.blur="total_tax_amount" type="text" class="form-control form-control-md" id="total_tax_amount" tabindex="0" disabled>
                </div>

                <div class="col-12 col-sm-4">
                    <label for="total_price_customer_including_tax" class="col-form-label col-form-label-md">Bedrag incl. BTW</label>
                    <input wire:model.blur="total_price_customer_including_tax" type="text" class="form-control form-control-md" id="total_price_customer_including_tax" tabindex="0" disabled>
                </div>
                

                <div class="col-6">
                    <label for="invoice_date" class="col-form-label col-form-label-md">Factuurdatum</label>
                    <input wire:model.blur="invoice_date" type="date" class="form-control form-control-md @error('invoice_date') is-invalid @enderror" tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('invoice_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6">
                    <label for="expiration_date" class="col-form-label col-form-label-md">Vervaldatum</label>
                    <input wire:model.blur="expiration_date" type="date" class="form-control form-control-md @error('expiration_date') is-invalid @enderror" tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('expiration_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6 col-sm-3">
                    <label for="order_id" class="col-form-label col-form-label-md">Opdracht ID</label>
                    <input wire:model.blur="order_id" type="number" class="form-control form-control-md @error('order_id') is-invalid @enderror" tabindex="0" @if ($edit === 0 OR isset($modelId)) disabled @endif>
                    @error('order_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6 col-sm-5">
                    <label for="order_title" class="col-form-label col-form-label-md">Opdracht titel</label>
                    <input wire:model.blur="order_title" type="text" class="form-control form-control-md @error('order_title') is-invalid @enderror" tabindex="0" disabled>
                    @error('order_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-4">
                    <label for="order_customer" class="col-form-label col-form-label-md">Klant</label>
                    <input wire:model.blur="order_customer" type="text" class="form-control form-control-md @error('order_customer') is-invalid @enderror" tabindex="0" disabled>
                    @error('order_customer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-lg-4">
                    <label for="custom_text" class="col-form-label col-form-label-md">Aangepaste tekst</label>
                    <textarea wire:model.blur="custom_text" type="text" class="form-control form-control-md @error('custom_text') is-invalid @enderror" id="custom_text" tabindex="0" rows="2" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('custom_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 col-lg-4">
                    <label for="status_comment" class="col-form-label col-form-label-md">Status Commentaar</label>
                    <textarea wire:model.blur="status_comment" type="text" class="form-control form-control-md @error('status_comment') is-invalid @enderror" id="status_comment" tabindex="0" rows="2" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('status_comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-lg-4">
                    <label for="extra_invoice_data" class="col-form-label col-form-label-md">Extra factuur data</label>
                    <textarea wire:model.blur="extra_invoice_data" type="text" class="form-control form-control-md @error('extra_invoice_data') is-invalid @enderror" id="extra_invoice_data" tabindex="0" rows="2" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('extra_invoice_data')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-auto mt-1">
                    <div class="form-check">
                        <input wire:model.blur="flush_cache" type="checkbox" id="flush_cache" class="form-check-input" @if ($edit === 0) disabled @endif>
                        <label for="flush_cache" class="form-check-label">Cache leegmaken <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Verwijder caching. Let op: Alle producten en uren worden opnieuw opgehaald uit de database, hierdoor kunnen deze verschillen van de huidige factuur."></i></label>
                        @error('flush_cache')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-auto mt-1">
                    <div class="form-check">
                        <input wire:model.blur="calculation_method_excluding_tax" type="checkbox" id="calculation_method_excluding_tax" class="form-check-input" @if ($edit === 0) disabled @endif>
                        <label for="calculation_method_excluding_tax" class="form-check-label">Rekenmethode exclusief BTW <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Rekent het factuur totaal uit op basis van de prijzen exclusief BTW, i.p.v. de standaard op basis van de prijzen inclusief BTW"></i></label>
                        @error('calculation_method_excluding_tax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if (count($order_hours) > 0)
                    <div class="col-12 mt-1">
                        <h5 class="@error('selected_order_hours') is-invalid @enderror">Uren</h5>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="10vw">Selectie</th>
                                    <th>Naam</th>
                                    <th width="10vw">Prijs</th>
                                    <th width="10vw">Aantal</th>
                                    <th width="10vw">Totaal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_hours as $order_hour)
                                    <tr>
                                        <td class="form-check text-center">
                                            <input wire:model.blur="selected_order_hours.{{ $order_hour->id }}" type="checkbox" id="order_hour.{{ $order_hour->id }}" class="form-check-input" @if($edit == 0) disabled @endif>
                                        </td>
                                        <td>{{ $order_hour->name }}</td>
                                        <td>€{{ $order_hour->price_customer_excluding_tax }}</td>
                                        <td>{{ $order_hour->amount }}</td>
                                        <td>€{{ $order_hour->amount_revenue_excluding_tax }}</td>
                                    </tr>
                                    @if (isset($selected_order_hours_comment[$order_hour->id]))
                                        <tr>
                                            <td colspan="100%">
                                                <label for="order_hour_comment.{{ $order_hour->id }}" class="col-form-label col-form-label-md">Commentaar voor uren</label>
                                                <input wire:model.blur="selected_order_hours_comment.{{ $order_hour->id }}" type="text" class="form-control form-control-md @error('selected_order_hours_comment.' . $order_hour->id) is-invalid @enderror" id="order_hour_comment.{{ $order_hour->id }}" required tabindex="0" @if($edit == 0) disabled @endif>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="100%">
                                            <label for="order_hour_description.{{ $order_hour->id }}" class="col-form-label col-form-label-md">Omschrijving</label>
                                            <textarea type="text" class="form-control form-control-md" id="order_hour_description.{{ $order_hour->id }}" disabled tabindex="0" rows="2">{{ $order_hour->description }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (count($order_products) > 0)
                    <div class="col-12 mt-1">
                        <h5 class="@error('selected_order_products') is-invalid @enderror">Producten</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="10vw">Selectie</th>
                                    <th>Naam</th>
                                    <th width="10vw">Prijs</th>
                                    <th width="10vw">Aantal</th>
                                    <th width="10vw">Totaal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    asort($order_products_order);
                                @endphp
                                @foreach ($order_products_order as $order_product_id => $order)
                                @php
                                    $order_product = $order_products->where('id', $order_product_id)->first();
                                @endphp
                                    <tr>
                                        <td class="form-check text-center">
                                            <input wire:model.live="selected_order_products.{{ $order_product->id }}" type="checkbox" id="order_product.{{ $order_product->id }}" class="form-check-input" @if($edit == 0) disabled @endif>
                                        </td>
                                        <td>{{ $order_product->name }}</td>
                                        <td>€{{ $order_product->price_customer_excluding_tax }}</td>
                                        <td>{{ $order_product->amount }}</td>
                                        <td>€{{ $order_product->revenue }}</td>
                                    </tr>
                                    @if (isset($selected_order_products_comment[$order_product->id]))
                                        <tr>
                                            <td colspan="100%">
                                                <label for="order_product_comment.{{ $order_product->id }}" class="col-form-label col-form-label-md">Opmerking</label>
                                                <input wire:model.blur="selected_order_products_comment.{{ $order_product->id }}" type="text" class="form-control form-control-md @error('selected_order_products_comment.' . $order_product->id) is-invalid @enderror" id="order_product_comment.{{ $order_product->id }}" tabindex="0" @if($edit == 0) disabled @endif>
                                            </td>
                                        </tr>
                                    @endif
                                    @if (count($order_products) > 1 && $edit == 1)
                                        <tr>
                                            <td colspan="100%">
                                                <div class="d-inline-flex justify-content-start">
                                                    @if (!$loop->first)
                                                        <div class="col">
                                                            <div wire:click="reorder_product({{ $order_product_id }}, 'up')">
                                                                <i class="fa fa-3x fa-caret-up"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (!$loop->last)
                                                        <div class="col">
                                                            <div wire:click="reorder_product({{ $order_product_id }}, 'down')">
                                                                <i class="fa fa-3x fa-caret-down"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p>Volgorde: {{ $order }}, id: {{ $order_product_id }}</p>
                                            </td>
                                        </tr> 
                                    @endif
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($edit === 0)
                <hr>

                <h3>Status historie</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Datum</th>
                            <th>Opmerking</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($previous_invoice_statuses as $previous_status)
                            <tr>
                                <td>{{ $previous_status['name'] }}</td>
                                <td>{{ Carbon\Carbon::parse($previous_status['pivot']['created_at'])->format('d-m-Y \o\m H:i') }}</td>
                                <td>{{ $previous_status['pivot']['comment'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">Geen data gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

                @if ($edit === 0)
                    <div class="col-12 col-md-6">
                        <label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
                        <input wire:model.blur="updated_at" type="date" class="form-control form-control-md" tabindex="0" disabled>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
                        <input wire:model.blur="created_at" type="date" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif
            </div>

            
            @if ($edit === 1)
                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="{{ route('invoice.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
                            Annuleren
                        </a>
                        <button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
                            <span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Opslaan
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
