<form wire:submit="store" class="my-1">
    @if ($status == 'weigeren')
        <h3>Offerte weigeren</h3>    
    @endif

    @if ($status == 'accepteren')
        <h3>Productselectie</h3>
        <div class="alert alert-info d-lg-none p-1" role="alert">
            <i class="fas fa-info-circle"></i>
            De tabel is horizontaal scrollbaar!
        </div>
        <div class="mb-1">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="10%">Selectie</th>
                            <th>Naam</th>
                            <th width="10%">Prijs {{ $quote->prices_exclude_tax ? 'excl. BTW' : 'incl. BTW' }}</th>
                            <th width="20%">Aantal</th>
                            <th width="10%">Totaal incl. BTW</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quote_products as $product)
                            <tr>
                                <td class="form-check text-center">
                                    <input wire:model.live="selected_products.{{ $product->id }}" type="checkbox" id="product.{{ $product->id }}" class="form-check-input">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    @if ($product->use_discount_prices == 1) 
                                        <s style="color: #8f8f8f;">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}</s> <span class="text-danger">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->discount_price_customer_excluding_tax : $product->discount_price_customer_including_tax), 2, '.', '') }}</span>
                                    @else
                                        €{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($selected_products[$product->id]))
                                        <input wire:model.live="selected_products_amount.{{ $product->id }}" type="number" step="1" class="form-control form-control-md @error('selected_products_amount.' . $product->id) is-invalid @enderror" id="selected_products_amount.{{ $product->id }}" required min="1" max="999">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($selected_products[$product->id]))
                                        €{{ $selected_products_total_cost_including_tax[$product->id] }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($quote_product_groups as $product_group)
                            @foreach ($product_group['products'] as $product)
                                <tr>
                                    <td class="form-check text-center">
                                        <input wire:model.live="selected_products_product_groups.{{ $product->id }}" type="checkbox" id="product_product_groups.{{ $product->id }}" class="form-check-input">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>@if ($product->use_discount_prices == 1) 
                                        <s style="color: #8f8f8f;">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}</s> <span class="text-danger">€{{ number_format(($quote->prices_exclude_tax == 1 ? $product->discount_price_customer_excluding_tax : $product->discount_price_customer_including_tax), 2, '.', '') }}</span>
                                    @else
                                        €{{ number_format(($quote->prices_exclude_tax == 1 ? $product->price_customer_excluding_tax : $product->price_customer_including_tax), 2, '.', '') }}
                                    @endif</td>
                                    <td>
                                        @if (isset($selected_products_product_groups[$product->id]))
                                            <input wire:model.live="selected_products_product_groups_amount.{{ $product->id }}" type="number" class="form-control form-control-md @error('selected_products_product_groups_amount.' . $product->id) is-invalid @enderror" id="selected_products_product_groups_amount.{{ $product->id }}" required min="1" max="999" step="1">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($selected_products_product_groups[$product->id]))
                                            €{{ $selected_products_product_groups_total_cost_including_tax[$product->id] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td colspan="4"><b>Totaal inclusief BTW</b></td>
                            <td><b>€{{ $total_cost }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mb-1">
        <label for="comment" class="col-form-label col-form-label-md">Opmerking</label>
        <textarea wire:model.live.500ms="comment" type="text" class="form-control form-control-md @error('comment') is-invalid @enderror" id="comment" tabindex="0" rows="4" placeholder="Laat een opmerking achter">
        </textarea>
        @error('comment')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if ($status == 'accepteren')
        <div class="mb-1">
            <div class="form-check form-check-lg">
                <input wire:model.live="terms_and_services" class="form-check-input @error('terms_and_services') is-invalid @enderror" type="checkbox" value="" id="terms_and_services">
                <label class="form-check-label" for="terms_and_services">
                Ik ga akkoord met de <a href="{{ $terms_and_services_link }}" target="_blank">algemene voorwaarden</a>.
                </label>
            </div>
            @error('terms_and_services')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    @if ($status == 'accepteren' AND $invoice_address_boolean == 1)
        <div class="mb-1">
            <hr>
            <h3>Factuuradres</h3>
            <div class="row">
                <div class="col-12 col-sm-8">
                    <label for="street" class="col-form-label col-form-label-md">Straat</label>
                    <input wire:model.live.debounce.500ms="street" type="text" class="form-control form-control-md @error('street') is-invalid @enderror" id="street" required tabindex="0">
                    @error('street')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-4">
                    <label for="number" class="col-form-label col-form-label-md">Nummer</label>
                    <input wire:model.live.debounce.500ms="number" type="text" class="form-control form-control-md @error('number') is-invalid @enderror" id="number" required tabindex="0">
                    @error('number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-7">
                    <label for="postal_code" class="col-form-label col-form-label-md">Postcode</label>
                    <input wire:model.live.debounce.500ms="postal_code" type="text" class="form-control form-control-md @error('postal_code') is-invalid @enderror" id="postal_code" required tabindex="0">
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-5">
                    <label for="place_name" class="col-form-label col-form-label-md">Plaats</label>
                    <input wire:model.live.debounce.500ms="place_name" type="text" class="form-control form-control-md @error('place_name') is-invalid @enderror" id="place_name" required tabindex="0">
                    @error('place_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mt-1">
                    <div class="col-form-check form-check-lg">
                        <input wire:model.live="company_boolean" class="@error('company_boolean') is-invalid @enderror" type="checkbox" id="company_boolean">
                        <label class="form-check-label" for="company_boolean">
                        Bedrijfsnaam toevoegen
                        </label>
                    </div>
                    @error('company_boolean')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                @if ($company_boolean == 1)
                    <div class="col-12">
                        <label for="company" class="col-form-label col-form-label-md">Bedrijfsnaam</label>
                        <input wire:model.live.debounce.500ms="company" type="text" class="form-control form-control-md @error('company') is-invalid @enderror" id="company" required tabindex="0">
                        @error('company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <button wire:target="store" wire:loading.class="btn-outline-primary waves-effect" wire:loading.class.remove="btn-primary" wire:loading.attr="disabled" type="submit" class="btn btn-primary" tabindex="0">
        <span wire:target="store" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Verzenden
    </button>
</form>
