<div class="card">
	<div class="card-body">
        <form wire:submit="store" class="my-1">
            <div class="row justify-content-between align-items-start mb-2">
				<div class="col-auto">
					<a href="{{ route('order.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
						<a class="btn btn-primary" href="{{ route('order.edit', $modelId) }}" tabindex="0">
							Aanpassen
						</a>
					</div>
				@endif
			</div>

            <div class="form-row mb-1">
                @if ($edit === 0)
                    <div class="col-4 col-md-2">
                        <label for="id" class="col-form-label col-form-label-md">ID</label>
                        <input wire:model.blur="modelId" type="integer" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif
                @can('show costs')
                    <div class="{{ $edit === 0 ? 'col-4 col-md-5 col-lg-2' : 'col-6 col-md-3' }}">
                        <label for="total_cost" class="col-form-label col-form-label-md">Kosten</label>
                        <input wire:model.blur="total_purchase_price_excluding_tax" type="text" class="form-control form-control-md" id="total_cost" tabindex="0" disabled>
                    </div>
                @endcan
                @can('show revenue')
                    <div class="{{ $edit === 0 ? 'col-4 col-md-5 col-lg-2' : 'col-6 col-md-3' }}">
                        <label for="total_revenue" class="col-form-label col-form-label-md">Winst</label>
                        <input wire:model.blur="total_profit" type="text" class="form-control form-control-md" id="total_revenue" tabindex="0" disabled>
                    </div>
                @endcan

                <div class="{{ $edit === 0 ? 'col-6 col-md-6 col-lg-3' : 'col-6 col-md-3' }}">
                    <label for="total_price_customer_excluding_tax" class="col-form-label col-form-label-md">Prijs excl. BTW</label>
                    <input wire:model.blur="total_price_customer_excluding_tax" type="text" class="form-control form-control-md" id="total_price_customer_excluding_tax" tabindex="0" disabled>
                </div>

                <div class="{{ $edit === 0 ? 'col-6 col-md-6 col-lg-3' : 'col-6 col-md-3' }}">
                    <label for="total_price_customer_including_tax" class="col-form-label col-form-label-md">Prijs incl. BTW</label>
                    <input wire:model.blur="total_price_customer_including_tax" type="text" class="form-control form-control-md" id="total_price_customer_including_tax" tabindex="0" disabled>
                </div>
                    
                <div class="col-12 {{ $edit === 0 ? 'col-lg-6 col-xl-4' : 'col-lg-4' }}">
                    <label for="title" class="col-form-label col-form-label-md">Titel</label>
                    <input wire:model.blur="title" type="text" class="form-control form-control-md @error('title') is-invalid @enderror" id="title" required tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6 {{ $edit === 0 ? 'col-sm-6 col-lg-6 col-xl-2' : 'col-md-6 col-lg-3' }}">
                    <div>
                        <label for="customer" class="col-form-label col-form-label-md">Klant</label>
                        <div wire:ignore>
                            <select name="customer" class="form-control form-control-md @error('customer') is-invalid @enderror" @if ($edit == 0) disabled @endif id="customer">
                                <option></option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($edit === 1)
                            @error('customer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                </div>

                <div class="{{ $edit === 0 ? 'col-6 col-sm-6 col-lg-4 col-xl-2' : 'col-6 col-md-6 col-lg-2' }}">
                    <label for="status" class="col-form-label col-form-label-md">Status</label>
                    <select wire:model.live="status" class="form-control form-control-md @error('status') is-invalid @enderror" id="status" @if ($edit === 0) disabled @endif>
                        <option value="" disabled hidden>Kies een status</option>
                        @foreach ($orderstatus as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($edit === 0)
                    <div class="col-6 col-lg-4 col-xl-2">
                        <label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
                        <input wire:model.blur="updated_at" type="date" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif

                <div class="{{ $edit === 0 ? 'col-6 col-lg-4 col-xl-2' : 'col-12 col-lg-3' }}">
                    <label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
                    <input wire:model.blur="created_at" type="date" class="form-control form-control-md @error('created_at') is-invalid @enderror" tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('created_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="col-form-label col-form-label-md">Beschrijving</label>
                    <textarea wire:model.blur="description" type="text" class="form-control form-control-md @error('description') is-invalid @enderror" id="description" tabindex="0" rows="3" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-6 col-xl-4 mt-1">
                    <div class="form-check">
                        <input wire:model.live.debounce.500ms="collapsed_view" type="checkbox" id="collapsed_view" class="form-check-input">
                        <label for="collapsed_view" class="form-check-label">Ingeklapte weergave</label>
                        @error('collapsed_view')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            @if ($edit == 0)
                <h3>Facturen</h3>

                <div class="mb-1">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Factuurnummer</th>
                                    <th>Status</th>
                                    <th>Factuurdatum</th>
                                    <th>Vervaldatum</th>
                                    <th>Actie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td class="table-{{ $invoice->invoice_statuses->first()->contextual_class }}">{{ $invoice->invoice_statuses->first()->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($invoice->expiration_date)->format('d-m-Y') }}</td>
                                        <td>
                                            <a class="btn btn-secondary" href="{{ route('invoice.show', $invoice->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a> 
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <span class="font-weight-bold">Er zijn geen facturen gevonden</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>
            @endif

            <h3>Uren</h3>

            <div class="mb-1">
                @forelse ($orderhours as $index => $orderhour)
                    <div class="form-row">
                        <div class="{{ $collapsed_view == true ? 'col-6' : 'col-12' }} col-sm-6 col-xl-3" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_name.{{ $index }}" class="col-form-label col-form-label-md">Uurtype</label>
                            <select wire:model.blur="hour_name.{{ $index }}" class="form-control form-control-md @error('hour_name.' . $index) is-invalid @enderror" id="hour_name.{{ $index }}" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                                <option value="" disabled hidden>Kies een uurtype</option>
                                @foreach ($hour_types as $hour_type)
                                    <option value="{{ $hour_type->name }}">{{ $hour_type->name }}</option>
                                @endforeach
                            </select>
                            @error('hour_name.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-xl-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="hour_user.{{ $index }}" class="col-form-label col-form-label-md">Gemaakt door</label>
                            <select wire:model.blur="hour_user.{{ $index }}" class="form-control form-control-md @error('hour_user.' . $index) is-invalid @enderror" id="hour_user.{{ $index }}" required>
                                <option value="">Kies een persoon</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('hour_user.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_tax_percentage.{{ $index }}" class="col-form-label col-form-label-md">Belasting</label>
                            <select wire:model.blur="hour_tax_percentage.{{ $index }}" class="form-control form-control-md @error('hour_tax_percentage.' . $index) is-invalid @enderror" id="hour_tax_percentage.{{ $index }}" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                                <option value="" disabled hidden>Kies een belastingtype</option>
                                @foreach ($tax_types as $tax_type)
                                    <option value="{{ $tax_type->percentage }}">{{ $tax_type->name }} ({{ $tax_type->percentage }}%)</option>
                                @endforeach
                            </select>
                            @error('hour_tax_percentage.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_price_customer_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Uurprijs excl. BTW</label>
                            <input wire:model.blur="hour_price_customer_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('hour_price_customer_excluding_tax.' . $index) is-invalid @enderror" id="hour_price_customer_excluding_tax.{{ $index }}" required tabindex="0" min="-99999.99" max="99999.99" step="0.01" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                            @error('hour_price_customer_excluding_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_price_customer_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Uurprijs incl. BTW</label>
                            <input wire:model.blur="hour_price_customer_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('hour_price_customer_including_tax.' . $index) is-invalid @enderror" id="hour_price_customer_including_tax.{{ $index }}" required tabindex="0" min="-99999.99" max="99999.99" step="0.01" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                            @error('hour_price_customer_including_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_date.{{ $index }}" class="col-form-label col-form-label-md">Datum</label>
                            <input wire:model.blur="hour_date.{{ $index }}" type="date" class="form-control form-control-md @error('hour_date.' . $index) is-invalid @enderror" id="hour_date.{{ $index }}" required tabindex="0" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                            @error('hour_date.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-md-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_start_time.{{ $index }}" class="col-form-label col-form-label-md">Starttijd</label>
                            <input wire:model.blur="hour_start_time.{{ $index }}" type="time" class="form-control form-control-md @error('hour_start_time.' . $index) is-invalid @enderror" id="hour_start_time.{{ $index }}" required tabindex="0" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                            @error('hour_start_time.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($hour_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="hour_end_time.{{ $index }}" class="col-form-label col-form-label-md">Eindtijd</label>
                            <input wire:model.blur="hour_end_time.{{ $index }}" type="time" class="form-control form-control-md @error('hour_end_time.' . $index) is-invalid @enderror" id="hour_end_time.{{ $index }}" required tabindex="0" @if ($edit === 0 OR $hour_edit[$index] == 0) disabled @endif>
                            @error('hour_end_time.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 col-lg-2 {{ $collapsed_view == true ? 'col-xl-3' : 'col-sm-4 col-xl-2' }}">
                            <label for="hour_amount.{{ $index }}" class="col-form-label col-form-label-md">Aantal uren</label>
                            <input wire:model.blur="hour_amount.{{ $index }}" type="number" class="form-control form-control-md @error('hour_amount.' . $index) is-invalid @enderror" id="hour_amount.{{ $index }}" tabindex="0" disabled min="0" max="99.99" step="0.01">
                            @error('hour_amount.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-lg-2 {{ $collapsed_view == true ? 'col-sm-6 col-xl-3' : 'col-sm-4 col-xl-2' }}">
                            <label for="hour_amount_price_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Prijs excl. BTW</label>
                            <input wire:model.blur="hour_amount_price_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('hour_amount_price_excluding_tax.' . $index) is-invalid @enderror" id="hour_amount_price_excluding_tax.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                            @error('hour_amount_price_excluding_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-sm-4 col-lg-2 {{ $collapsed_view == true ? 'col-sm-6 col-xl-3' : 'col-sm-4 col-xl-2' }}">
                            <label for="hour_amount_price_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Prijs incl. BTW</label>
                            <input wire:model.blur="hour_amount_price_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('hour_amount_price_including_tax.' . $index) is-invalid @enderror" id="hour_amount_price_including_tax.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                            @error('hour_amount_price_including_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-lg-3 col-xl-6 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="hour_kilometers.{{ $index }}" class="col-form-label col-form-label-md">Kilometers</label>
                            <input wire:model.blur="hour_kilometers.{{ $index }}" type="number" class="form-control form-control-md @error('hour_kilometers.' . $index) is-invalid @enderror" id="hour_kilometers.{{ $index }}" tabindex="0">
                            @error('hour_kilometers.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 col-lg-3 col-xl-6 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="hour_time_minutes.{{ $index }}" class="col-form-label col-form-label-md">Reistijd (minuten)</label>
                            <input wire:model.blur="hour_time_minutes.{{ $index }}" type="number" class="form-control form-control-md @error('hour_time_minutes.' . $index) is-invalid @enderror" id="hour_time_minutes.{{ $index }}" tabindex="0">
                            @error('hour_time_minutes.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="hour_description.{{ $index }}" class="col-form-label col-form-label-md">Beschrijving</label>
                            <textarea wire:model.blur="hour_description.{{ $index }}" type="text" class="form-control form-control-md @error('hour_description.' . $index) is-invalid @enderror" id="hour_description.{{ $index }}" required tabindex="0" rows="2">
                            </textarea>
                            @error('hour_description.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($edit === 0)
                            <div class="col-12 col-sm-6">
                                <label for="hour_updated_at.{{ $index }}" class="col-form-label col-form-label-md">Aangepast op</label>
                                <input wire:model.blur="hour_updated_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="hour_created_at.{{ $index }}" class="col-form-label col-form-label-md">Gemaakt op</label>
                                <input wire:model.blur="hour_created_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
                            </div>
                        @endif
                        
                        @if ($edit === 1)
                            <div class="col-12 my-1">
                                @if ($loop->last)
                                    <button type="button" id="add_hours_{{ $index }}" wire:click="addHours" class="btn btn-primary" tabindex="0" wire:key="hours_edit_add_{{ $index }}">
                                        <span wire:target="addHours" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        + Uren toevoegen
                                    </button>
                                @endif
                                <button type="button" id="remove_hours_{{ $index }}" wire:click="removeHours({{ $index }})" class="btn btn-danger" tabindex="0" wire:key="hours_edit_remove_{{ $index }}">
                                    <span wire:target="removeHours({{ $index }})" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    - Uren verwijderen
                                </button>
                            </div>
                        @endif
                    </div>
                    @if (!$loop->last)
                        <hr>
                    @endif
                @empty
                    <p>Er zijn geen uren toegevoegd</p>
                    @if ($edit === 1 AND count($orderhours) === 0)
                    <button type="button" wire:click="addHours"class="btn btn-primary" tabindex="0" wire:key="add_hours_empty">
                        <span wire:target="addHours" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        + Uren toevoegen
                    </button>
                    @endif
                @endforelse
            </div>

            <hr>

            <h3>Producten</h3>

            <div class="mb-1">
                @php
                    asort($orderproducts)
                @endphp
                @forelse ($orderproducts as $index => $orderproduct)
                    <div class="form-row">
                        <div class="col-12 col-lg-6 {{ $collapsed_view == false ? 'col-xl-4' : 'col-xl-3' }}">
                            <label for="product_name.{{ $index }}" class="col-form-label col-form-label-md">Product</label>
                            @if ($edit === 1 AND $product_edit[$index] == 1)
                                <div class="input-group">
                            @endif
                            <input wire:model.blur="product_name.{{ $index }}" type="text" class="form-control form-control-md @error('product_name.' . $index) is-invalid @enderror" id="product_name.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif>
                            @if ($edit === 1 AND $product_edit[$index] == 1)
                                <div class="input-group-append">
                                    <div class="btn btn-primary" wire:click="ProductSelector({{ $index }})"><i class="fas fa-toolbox"></i></div>
                                </div>
                            </div>
                            @endif
                            @error('product_name.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_supplier.{{ $index }}" class="col-form-label col-form-label-md">Leverancier</label>
                            <input wire:model.blur="product_supplier.{{ $index }}" type="text" class="form-control form-control-md @error('product_supplier.' . $index) is-invalid @enderror" id="product_supplier.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif>
                            @error('product_supplier.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_order_number.{{ $index }}" class="col-form-label col-form-label-md">Bestelnummer</label>
                            <input wire:model.blur="product_order_number.{{ $index }}" type="text" class="form-control form-control-md @error('product_order_number.' . $index) is-invalid @enderror" id="product_order_number" tabindex="0" @if ($edit === 0) disabled @endif>
                            @error('product_order_number.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_user.{{ $index }}" class="col-form-label col-form-label-md">Besteld door</label>
                            <select wire:model.blur="product_user.{{ $index }}" class="form-control form-control-md @error('product_user.' . $index) is-invalid @enderror" id="product_user.{{ $index }}" @if ($edit === 0) disabled @endif>
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('product_user.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_tax_percentage.{{ $index }}" class="col-form-label col-form-label-md">Belasting</label>
                            <select wire:model.blur="product_tax_percentage.{{ $index }}" class="form-control form-control-md @error('product_tax_percentage.' . $index) is-invalid @enderror" id="product_tax_percentage.{{ $index }}" @if ($edit === 0 OR $product_edit[$index] == 0) disabled @endif>
                                <option value="" disabled hidden>Kies een belastingtype</option>
                                @foreach ($tax_types as $tax_type)
                                    <option value="{{ $tax_type->percentage }}">{{ $tax_type->name }} ({{ $tax_type->percentage }}%)</option>
                                @endforeach
                            </select>
                            @error('product_tax_percentage.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-3 col-xl-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_purchase_price_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Inkoop excl. BTW</label>
                            <input wire:model.blur="product_purchase_price_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_purchase_price_excluding_tax.' . $index ) is-invalid @enderror" id="product_purchase_price_excluding_tax.{{ $index }}" required tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                            @error('product_purchase_price_excluding_tax.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-3 col-xl-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_purchase_price_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Inkoop incl. BTW</label>
                            <input wire:model.blur="product_purchase_price_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_purchase_price_including_tax.' . $index ) is-invalid @enderror" id="product_purchase_price_including_tax.{{ $index }}" required tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                            @error('product_purchase_price_including_tax.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-3 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($product_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="product_price_customer_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Prijs excl. BTW</label>
                            <input wire:model.blur="product_price_customer_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_price_customer_excluding_tax.' . $index) is-invalid @enderror" id="product_price_customer_excluding_tax.{{ $index }}" required tabindex="0" @if ($edit === 0 OR $product_edit[$index] == 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                            @error('product_price_customer_excluding_tax.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-3 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($product_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="product_price_customer_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Prijs incl. BTW</label>
                            <input wire:model.blur="product_price_customer_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_price_customer_including_tax.' . $index) is-invalid @enderror" id="product_price_customer_including_tax.{{ $index }}" required tabindex="0" @if ($edit === 0 OR $product_edit[$index] == 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                            @error('product_price_customer_including_tax.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-2 col-xl-3 {{ $collapsed_view == false ? '' : 'd-none' }}" @if ($product_edit[$index] == 0 AND $edit == 1) wire:click="editNotAllowed" @endif>
                            <label for="product_amount.{{ $index }}" class="col-form-label col-form-label-md">Aantal</label>
                            <input wire:model.blur="product_amount.{{ $index }}" type="number" class="form-control form-control-md @error('product_amount.' . $index) is-invalid @enderror" id="product_amount.{{ $index }}" required tabindex="0" @if ($edit === 0 OR $product_edit[$index] == 0) disabled @endif min="-9999.99" max="9999.99" step="0.01">
                            @error('product_amount.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-3 col-xl-3 col-xl-3">
                            <label for="product_revenue.{{ $index }}" class="col-form-label col-form-label-md">Totaal ex BTW</label>
                            <input wire:model.blur="product_revenue.{{ $index }}" type="number" class="form-control form-control-md @error('product_revenue.' . $index) is-invalid @enderror" id="product_revenue.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                            @error('product_revenue.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-3 col-xl-3 col-xl-3">
                            <label for="product_total_price_customer_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Totaal incl. BTW</label>
                            <input wire:model.blur="product_total_price_customer_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_total_price_customer_including_tax.' . $index) is-invalid @enderror" id="product_total_price_customer_including_tax.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                            @error('product_total_price_customer_including_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 col-md-4 col-lg-4  col-xl-3 col-xl-3">
                            <label for="product_profit.{{ $index }}" class="col-form-label col-form-label-md">Winst</label>
                            <input wire:model.blur="product_profit.{{ $index }}" type="number" class="form-control form-control-md @error('product_profit.' . $index) is-invalid @enderror" id="product_profit.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                            @error('product_profit.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_description.{{ $index }}" class="col-form-label col-form-label-md">Beschrijving</label>
                            <textarea wire:model.blur="product_description.{{ $index }}" type="text" class="form-control form-control-md @error('product_description.' . $index) is-invalid @enderror" id="product_description.{{ $index }}" tabindex="0" rows="{{ $product_description_count[$index] }}" @if ($edit === 0) disabled @endif>
                            </textarea>
                            @error('product_description.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_updated_at.{{ $index }}" class="col-form-label col-form-label-md">Aangepast op</label>
                            <input wire:model.blur="product_updated_at.{{ $index }}" type="date" class="form-control form-control-md" tabindex="0" disabled>
                        </div>

                        <div class="col-12 col-sm-6 {{ $collapsed_view == false ? '' : 'd-none' }}">
                            <label for="product_created_at.{{ $index }}" class="col-form-label col-form-label-md">Gemaakt op</label>
                            <input wire:model.blur="product_created_at.{{ $index }}" type="date" class="form-control form-control-md @error('product_created_at.' . $index) is-invalid @enderror" tabindex="0" @if ($edit === 0) disabled @endif>
                        </div>

                        @if (count($orderproducts) > 1 && $edit == 1)
                            <div class="mb-1">
                                <div class="d-inline-flex justify-content-start">
                                    @if (!$loop->first)
                                        <div class="col">
                                            <div wire:click="reorder_product({{ $index }}, 'up')">
                                                <i class="fa fa-3x fa-caret-up"></i>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$loop->last)
                                        <div class="col">
                                            <div wire:click="reorder_product({{ $index }}, 'down')">
                                                <i class="fa fa-3x fa-caret-down"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($edit === 1)
                            <div class="col-12 my-1">
                                @if ($loop->last)
                                    <button type="button" id="add_product_{{ $index }}" wire:click="addProduct" class="btn btn-primary" tabindex="0" wire:key="product_edit_add_{{ $index }}">
                                        <span wire:target="addProduct" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        + Product toevoegen
                                    </button>
                                @endif
                                @if ($product_edit[$index] != 0)
                                    <button type="button" id="remove_product_{{ $index }}" wire:click="removeProduct('{{ $index }}')" class="btn btn-danger" tabindex="0" wire:key="product_edit_remove_{{ $index }}">
                                        <span wire:target="removeProduct('{{ $index }}')" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        - Product verwijderen
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if (!$loop->last)
                        <hr>
                    @endif
                @empty
                    <p>Er zijn geen producten toegevoegd</p>
                    @if ($edit === 1 AND count($orderproducts) === 0)
                        <button type="button" wire:click="addProduct"class="btn btn-primary" tabindex="0" wire:key="add_product_empty">
                            <span wire:target="addProduct" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            + Product toevoegen
                        </button>
                    @endif
                @endforelse
            </div>

            @if ($edit === 1)
                <hr>

                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="{{ route('customer.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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

@push('page-scripts')
    <script>
        $(document).ready(function(){
            function select2_customer() {
                $('#customer').select2({
                    placeholder: "Kies een klant",
                }).prepend('<option selected=""></option>');
            }

            $('#customer').on('change', function() {
                let customer_id = $(this).val();
                @this.set('customer', customer_id)
            });

            select2_customer();
            $('#customer').val(@this.get('customer')).change();
        });
    </script>
@endpush
