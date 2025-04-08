<div class="card">
	<div class="card-body">
        <form wire:submit="store" class="my-1" @if ($edit == 1) wire:sortable="orderUpdated" @endif>
            <div class="row justify-content-between align-items-start mb-2">
				<div class="col-auto">
					<a href="{{ route('quote.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
						<a class="btn btn-primary" href="{{ route('quote.edit', $modelId) }}" tabindex="0">
							Aanpassen
						</a>
					</div>
				@endif
			</div>

            @if ($edit === 0)
                <div class="mb-1">
                    <label for="id" class="col-form-label col-form-label-md">ID</label>
                    <input wire:model.blur="modelId" type="integer" class="form-control form-control-md" tabindex="0" disabled>
                </div>
            @endif
            <div class="form-row mb-1">
                <div class="col-12 col-lg-4">
                    <label for="title" class="col-form-label col-form-label-md">Titel</label>
                    <input wire:model.blur="title" type="text" class="form-control form-control-md @error('title') is-invalid @enderror" id="title" required tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-lg-4">
                    <div>
                        <label for="customer" class="col-form-label col-form-label-md">Klant</label>
                        <div wire:ignore>
                            <select name="customer" class="form-control form-control-md @error('customer') is-invalid @enderror" id="customer">
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

                <div class="col-12 col-lg-4">
                    <label for="status" class="col-form-label col-form-label-md">Status</label>
                    <select wire:model.live="status" class="form-control form-control-md @error('status') is-invalid @enderror" id="status" @if ($edit === 0) disabled @endif>
                        <option value="" disabled hidden>Kies een status</option>
                        @foreach ($quotestatus as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if ($edit === 0)
                    <div class="col-12 col-lg-6">
                        <label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
                        <input wire:model.blur="updated_at" type="date" class="form-control form-control-md" tabindex="0" disabled>
                    </div>

                    <div class="col-12 col-lg-6">
                        <label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
                        <input wire:model.blur="created_at" type="date" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif

                <div class="col-12">
                    <label for="description" class="col-form-label col-form-label-md">Beschrijving</label>
                    <textarea wire:model.blur="description" type="text" class="form-control form-control-md @error('description') is-invalid @enderror" id="description" tabindex="0" rows="2" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-lg-6">
                    <label for="expiration_date" class="col-form-label col-form-label-md">Vervaldatum</label>
                    <input wire:model.blur="expiration_date" type="date" class="form-control form-control-md @error('expiration_date') is-invalid @enderror" tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('expiration_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-lg-6">
                    <label for="status_comment" class="col-form-label col-form-label-md">Status Commentaar</label>
                    <textarea wire:model.blur="status_comment" type="text" class="form-control form-control-md @error('status_comment') is-invalid @enderror" id="status_comment" tabindex="0" rows="1" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('status_comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="quote_text" class="col-form-label col-form-label-md">Offerte tekst <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Je kunt gebruik maken van de volgende variabelen: ['first_name'], ['full_name'], ['email'], ['company'], ['quote_title'], ['party_date'], ['start_time'], ['end_time'], ['party_type'], ['location'], ['guest_amount']"></i></label>
                    <textarea wire:model.blur="quote_text" type="text" class="form-control form-control-md @error('quote_text') is-invalid @enderror" id="quote_text" tabindex="0" rows="8" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('quote_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <h3>Opties</h3>

            <div class="mb-1">
                <div class="row">
                    @if ($edit === 1)
                        <div class="col-6 col-xl-4">
                            <div class="form-check">
                                <input wire:model.live.debounce.500ms="flush_cache" type="checkbox" id="flush_cache" class="form-check-input" @if ($edit === 0) disabled @endif>
                                <label for="flush_cache" class="form-check-label">Cache leegmaken <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Verwijder caching. Let op: Alle prijzen en producten worden opnieuw van de database opgehaald, hierdoor kunnen deze verschillen van de huidige offerte."></i></label>
                                @error('flush_cache')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-6 col-xl-4">
                        <div class="form-check">
                            <input wire:model.live.debounce.500ms="prices_exclude_tax" type="checkbox" id="prices_exclude_tax" class="form-check-input" @if ($edit === 0) disabled @endif>
                            <label for="prices_exclude_tax" class="form-check-label">Prijzen zijn exclusief BTW <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Deze optie haalt de text onderaan weg dat alle prijzen inclusief BTW zijn en zet er neer dat alle prijzen exclusief BTW zijn."></i></label>
                            @error('prices_exclude_tax')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="form-check">
                            <input wire:model.live.debounce.500ms="show_product_group_images" type="checkbox" id="show_product_group_images" class="form-check-input" @if ($edit === 0) disabled @endif>
                            <label for="show_product_group_images" class="form-check-label">Product afbeeldingen voor product groepen laten zien</label>
                            @error('show_product_group_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="form-check">
                            <input wire:model.live.debounce.500ms="show_amount_and_total" type="checkbox" id="show_amount_and_total" class="form-check-input" @if ($edit === 0) disabled @endif>
                            <label for="show_amount_and_total" class="form-check-label">Aantal en totaal weergeven</label>
                            @error('show_amount_and_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="form-check">
                            <input wire:model.live.debounce.500ms="collapsed_view" type="checkbox" id="collapsed_view" class="form-check-input">
                            <label for="collapsed_view" class="form-check-label">Ingeklapte weergave</label>
                            @error('collapsed_view')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 col-xl-4">
                        <div class="form-check">
                            <input wire:model.live.debounce.500ms="show_packages" type="checkbox" id="show_packages" class="form-check-input" @if ($edit === 0) disabled @endif>
                            <label for="show_packages" class="form-check-label">Pakketten weergeven</label>
                            @error('show_packages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            @if ($show_packages == true)

                <h3>Pakketten</h3>
                @error('selected_product_groups')
                    <div class="alert-danger">{{ $message }}</div>
                @enderror

                <div class="mb-1">
                    <div class="custom-file {{ $collapsed_view == false ? '' : 'd-none' }}">
                        @if ($edit == 1)
                            <label for="new_package_image" class="form-label custom-file-label">Upload afbeelding</label>
                            <input class="form-control custom-file-input @error('new_package_image') is-invalid @enderror" type="file" id="new_package_image" wire:model.live.debounce.500ms="new_package_image" tabindex="0"> 
                            @error('new_package_image')
                                <div class="invalid-feedback" for="new_package_image">{{ $message }}</div>
                            @enderror
                            <div wire:target="new_package_image" class="d-none spinner-border text-primary" wire:loading.class.remove="d-none" role="status">
                                <span class="sr-only">Uploaden...</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    @if (!empty($package_image))
                        <div class="col-12 col-md-6">
                            <img src="{{ asset($package_image) }}" alt="Huidige pakket afbeelding" class="w-100">
                            <p>Huidige pakket afbeelding</p>
                            @if ($edit == 1)
                                <div wire:click="removePackageImage" class="btn btn-danger">
                                    <span wire:target="removePackageImage" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                        <i class="fa fa-trash"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                    @if (!empty($new_package_image))
                        <div class="col-12 col-md-6">
                            <img src="{{ asset($new_package_image->temporaryUrl()) }}" alt="Nieuwe pakket afbeelding" class="w-100">
                            <p>Nieuwe pakket afbeelding</p>
                            <div wire:click="removeNewPackageImage" class="btn btn-danger">
                                <span wire:target="removeNewPackageImage" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <hr>
            @endif

            <h3>Product groepen</h3>
            @error('selected_product_groups')
                <div class="alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-1">
                <div class="row">
                    @foreach ($product_groups as $product_group)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="form-check">
                                <input wire:model.live.debounce.500ms="selected_product_groups.{{ $product_group->id }}" type="checkbox" id="product_group.{{ $product_group->id }}" class="form-check-input" @if ($edit === 0) disabled @endif>
                                <label for="product_group.{{ $product_group->id }}" class="form-check-label">{{ $product_group->name }}</label>
                                @error('selected_product_groups.' . $product_group->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <h3>Producten</h3>
            <div class="mb-1" @if ($edit == 1) wire:sortable.options="{ animation: 100 }" wire:sortable-group="orderUpdated" @endif>
                @php
                    asort($quoteproducts);
                @endphp
                @forelse ($quoteproducts as $index => $order)
                    <div class="form-row" wire:key="quote_products_{{ $index }}">
                        <div class="col-12 col-lg-6">
                            <label for="product_name.{{ $index }}" class="col-form-label col-form-label-md">Product</label>
                            @if ($edit === 1)
                                <div class="input-group">
                            @endif
                            <input wire:model.blur="product_name.{{ $index }}" type="text" class="form-control form-control-md @error('product_name.' . $index) is-invalid @enderror" id="product_name.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif>
                            @if ($edit === 1)
                                <div class="input-group-append">
                                    <div class="btn btn-primary" wire:click="ProductSelector({{ $index }})"><i class="fas fa-toolbox"></i></div>
                                </div>
                            </div>
                            @endif
                            @error('product_name.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="col-6 col-lg-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_purchase_price_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Inkoopprijs excl. BTW</label>
                                <input wire:model.blur="product_purchase_price_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_purchase_price_excluding_tax.' . $index ) is-invalid @enderror" id="product_purchase_price_excluding_tax.{{ $index }}" required tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                                @error('product_purchase_price_excluding_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-lg-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_purchase_price_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Inkoopprijs incl. BTW</label>
                                <input wire:model.blur="product_purchase_price_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_purchase_price_including_tax.' . $index ) is-invalid @enderror" id="product_purchase_price_including_tax.{{ $index }}" required tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                                @error('product_purchase_price_including_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-lg-3 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_price_customer_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Klantprijs excl. BTW</label>
                                <input wire:model.blur="product_price_customer_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_price_customer_excluding_tax.' . $index) is-invalid @enderror" id="product_price_customer_excluding_tax.{{ $index }}" required tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                                @error('product_price_customer_excluding_tax.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-lg-3 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_price_customer_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Klantprijs incl. BTW</label>
                                <input wire:model.blur="product_price_customer_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_price_customer_including_tax.' . $index) is-invalid @enderror" id="product_price_customer_including_tax.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                                @error('product_price_customer_including_tax.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-5 col-lg-2 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_amount.{{ $index }}" class="col-form-label col-form-label-md">Aantal</label>
                                <input wire:model.blur="product_amount.{{ $index }}" type="number" class="form-control form-control-md @error('product_amount.' . $index) is-invalid @enderror" id="product_amount.{{ $index }}" required tabindex="0" @if ($edit === 0) disabled @endif min="-99.99" max="99.99" step="0.01">
                                @error('product_amount.' . $index)
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-7 col-lg-4 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_tax_percentage.{{ $index }}" class="col-form-label col-form-label-md">Belasting</label>
                                <select wire:model.blur="product_tax_percentage.{{ $index }}" class="form-control form-control-md @error('product_tax_percentage.' . $index) is-invalid @enderror" id="product_tax_percentage.{{ $index }}" @if ($edit === 0) disabled @endif>
                                    <option value="" disabled hidden>Kies een belastingtype</option>
                                    @foreach ($tax_types as $tax_type)
                                        <option value="{{ $tax_type->percentage }}">{{ $tax_type->name }} ({{ $tax_type->percentage }}%)</option>
                                    @endforeach
                                </select>
                                @error('product_tax_percentage.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_total_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Totaal product excl. BTW</label>
                                <input wire:model.blur="product_total_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_total_excluding_tax.' . $index) is-invalid @enderror" id="product_total_excluding_tax.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                                @error('product_total_excluding_tax.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-xl-2 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_total_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Totaal product incl. BTW</label>
                                <input wire:model.blur="product_total_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('product_total_including_tax.' . $index) is-invalid @enderror" id="product_total_including_tax.{{ $index }}" tabindex="0" disabled min="-99999999.99" max="99999999.99" step="0.01">
                                @error('product_total_including_tax.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-auto mt-1 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <div class="form-check">
                                    <input wire:model.live.debounce.500ms="product_show_images.{{ $index }}" type="checkbox" id="product_show_images.{{ $index }}" class="form-check-input" @if ($edit === 0) disabled @endif>
                                    <label for="product_show_images.{{ $index }}" class="form-check-label">Product afbeeldingen weergeven</label>
                                    @error('product_show_images.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-auto mt-1 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <div class="form-check">
                                    <input wire:model.live.debounce.500ms="use_discount_prices.{{ $index }}" type="checkbox" id="use_discount_prices.{{ $index }}" class="form-check-input" @if ($edit === 0) disabled @endif>
                                    <label for="use_discount_prices.{{ $index }}" class="form-check-label">Kortingsprijs gebruiken</label>
                                    @error('use_discount_prices.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if ($use_discount_prices[$index] == true)
                                <div class="w-100"></div>

                                <div class="col-6 col-lg-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                    <label for="discount_price_customer_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Kortingsprijs product excl. BTW</label>
                                    <input wire:model.blur="discount_price_customer_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('discount_price_customer_excluding_tax.' . $index ) is-invalid @enderror" id="discount_price_customer_excluding_tax.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                                    @error('discount_price_customer_excluding_tax.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 col-lg-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                    <label for="discount_price_customer_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Kortingsprijs product incl. BTW</label>
                                    <input wire:model.blur="discount_price_customer_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('discount_price_customer_including_tax.' . $index ) is-invalid @enderror" id="discount_price_customer_including_tax.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                                    @error('discount_price_customer_including_tax.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 col-lg-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                    <label for="total_discount_price_customer_excluding_tax.{{ $index }}" class="col-form-label col-form-label-md">Totale kortingsprijs product excl. BTW</label>
                                    <input wire:model.blur="total_discount_price_customer_excluding_tax.{{ $index }}" type="number" class="form-control form-control-md @error('total_discount_price_customer_excluding_tax.' . $index) is-invalid @enderror" id="total_discount_price_customer_excluding_tax.{{ $index }}" tabindex="0" disabled min="-99999.99" max="99999.99" step="0.01">
                                    @error('total_discount_price_customer_excluding_tax.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6 col-lg-3 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                    <label for="total_discount_price_customer_including_tax.{{ $index }}" class="col-form-label col-form-label-md">Totale kortingsprijs product incl. BTW</label>
                                    <input wire:model.blur="total_discount_price_customer_including_tax.{{ $index }}" type="number" class="form-control form-control-md @error('total_discount_price_customer_including_tax.' . $index) is-invalid @enderror" id="total_discount_price_customer_including_tax.{{ $index }}" tabindex="0" disabled min="-99999.99" max="99999.99" step="0.01">
                                    @error('total_discount_price_customer_including_tax.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-12 {{ $collapsed_view == false ? '' : 'd-none' }}">
                                <label for="product_highlight_text.{{ $index }}" class="col-form-label col-form-label-md">Uitgelicht text</label>
                                <input wire:model.blur="product_highlight_text.{{ $index }}" type="text" class="form-control form-control-md @error('product_highlight_text.' . $index) is-invalid @enderror" id="product_highlight_text.{{ $index }}" tabindex="0" @if ($edit === 0) disabled @endif>
                                @error('product_highlight_text.' . $index)
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

                            @if ($edit === 0)
                                <div class="col-12 col-sm-6">
                                    <label for="product_updated_at.{{ $index }}" class="col-form-label col-form-label-md">Aangepast op</label>
                                    <input wire:model.blur="product_updated_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="product_created_at.{{ $index }}" class="col-form-label col-form-label-md">Gemaakt op</label>
                                    <input wire:model.blur="product_created_at.{{ $index }}" type="text" class="form-control form-control-md" tabindex="0" disabled>
                                </div>
                            @endif
                    </div>

                    {{-- Upload images --}}
                    <div class="mb-2 mt-1 custom-file {{ $collapsed_view == false ? '' : 'd-none' }}">
                        @if ($edit == 1)
                            <label for="new_images.{{ $index }}" class="form-label custom-file-label">Afbeeldingen toevoegen</label>
                            <input class="form-control custom-file-input @error('new_images.' . $index . '.*') is-invalid @enderror @error('images.' . $index) is-invalid @enderror" type="file" id="new_images.{{ $index }}" wire:model.live.debounce.500ms="new_images.{{ $index }}" tabindex="0" multiple> 
                            @error('new_images.' . $index . '.*')
                                <div class="invalid-feedback" for="new_images.{{ $index }}">{{ $message }}</div>
                            @enderror
                            @error('images.' . $index)
                                <div class="invalid-feedback" for="new_images.{{ $index }}">{{ $message }}</div>
                            @enderror
                            <div wire:target="new_images.{{ $index }}" class="d-none spinner-border text-primary" wire:loading.class.remove="d-none" role="status">
                                <span class="sr-only">Uploaden...</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-1 {{ $collapsed_view == false ? '' : 'd-none' }}" >
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 g-4" @if ($edit == 1)  wire:sortable-group.item-group="{{ $index }}"  @endif>
                            @foreach ($image_order[$index] as $index_image => $image)
                                <div class="col" wire:key="item-{{ $index_image }}" @if ($edit == 1) wire:sortable-group.item="{{ $index_image }}" wire:sortable-group.handle @endif>
                                    <div class="card h-100">
                                        <div class="ratio ratio-16x9">
                                            <img src="{{ asset($image_link[$index][$index_image]) }}" class="card-img-top" alt="Product afbeelding {{ $loop->index }}" style="vertical-align: middle; object-fit: cover;">
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">Volgorde: {{ $image }}</p>
                                            @if ($edit == 1)
                                                <div wire:click="removeImage({{ $index }},{{ $index_image }})" class="btn btn-danger">
                                                    <span wire:target="removeImage.{{ $index }}.{{ $index_image }}" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                                        <i class="fa fa-trash"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if (count($quoteproducts) > 1 && $edit == 1)
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

                    <div class="form-row">                
                        @if ($edit === 1)
                            <div class="col-12 my-1">
                                @if ($loop->last)
                                    <button type="button" id="add_product_{{ $index }}" wire:click="addProduct" class="btn btn-primary" tabindex="0" wire:key="product_edit_add_{{ $index }}">
                                        <span wire:target="addProduct" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        + Product toevoegen
                                    </button>
                                @endif
                                <button type="button" id="remove_product_{{ $index }}" wire:click="removeProduct({{ $index }})" class="btn btn-danger" tabindex="0" wire:key="product_edit_remove_{{ $index }}">
                                    <span wire:target="removeProduct({{ $index }})" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    - Product verwijderen
                                </button>
                            </div>
                        @endif
                    </div>
                    @if (!$loop->last)
                        <hr>
                    @endif
                @empty
                    <p>Er zijn geen producten toegevoegd</p>
                    @if ($edit === 1 AND count($quoteproducts) === 0)
                        <button type="button" wire:click="addProduct"class="btn btn-primary" tabindex="0" wire:key="add_product_empty">
                            <span wire:target="addProduct" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            + Product toevoegen
                        </button>
                    @endif
                @endforelse
            </div>
            
            @if (count($selected_quote_products) > 0)
                <hr>

                <h3>Geselecteerde producten</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Prijs excl. BTW</th>
                            <th>Aantal</th>
                            <th>Product totaal excl. BTW</th>
                            @can('delete selectedquoteproduct')
                                @if ($edit == 1)
                                    <th>Actie</th>
                                @endif
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selected_quote_products as $selected_quote_product)
                            <tr>
                                <td>{{ $selected_quote_product->name }}</td>
                                <td>{{ $selected_quote_product->price_customer_excluding_tax }}</td>
                                <td>{{ $selected_quote_product->amount }}</td>
                                <td>{{ $selected_quote_product->total_price_customer_excluding_tax }}</td>
                                @can('delete selectedquoteproduct')
                                    @if ($edit == 1)
                                        <td>
                                            <button type="button" id="deleteSelectedQuoteProduct_{{ $selected_quote_product->id }}" class="btn btn-danger" wire:click="removeSelectedQuoteProduct('{{ $selected_quote_product->id }}')" wire:key="deleteSelectedQuoteProductButton_{{ $selected_quote_product->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    @endif
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($edit === 0)
                <hr>

                <h3>Status historie</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Commentaar</th>
                            <th>Datum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($previous_quote_statuses as $previous_status)
                            <tr>
                                <td>{{ $previous_status['name'] }}</td>
                                <td>{{ $previous_status['pivot']['comment'] }}</td>
                                <td>{{ Carbon\Carbon::parse($previous_status['pivot']['created_at'])->format('d-m-Y | H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">Geen data gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($edit === 1)
                <hr>

                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="{{ route('quote.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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

            function select2_location() {
                $('#location').select2({
                    placeholder: "Kies een locatie",
                }).prepend('<option selected=""></option>');
            }

            $('#location').on('change', function() {
                let location_name = $(this).val();
                @this.set('location', location_name)
            });

            select2_location();
            $('#location').val(@this.get('location')).change();
        });
    </script>
@endpush
