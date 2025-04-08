<div class="card">
    <div class="card-header">
        <h4 class="card-title"><a href="{{ route('product.create') }}" class="btn btn-primary">+ Nieuwe product</a></h4>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="fas fa-chevron-down"></i></a></li>
                <li wire:click="$refresh"><a data-action="reload"><i class="fas fa-sync"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse show" aria-expanded="true">
        <div class="card-body">
            <div class="row mb-1">
                <div class="col-12 col-md-6">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>
                                Items per pagina:&nbsp;&nbsp;
                            </label>
                            <select class="form-control" wire:model.live="paginationItemsAmount">
                                <option selected value="15">15</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="0">Alles</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 align-right">
                    <input wire:model.live.debounce.250ms="search" type="text" class="form-control form-control-md" id="search" placeholder="Zoeken..." tabindex="0">
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showProductImages" wire:model.live="showProductImages">
                        <label class="form-check-label" for="showProductImages">Product afbeeldingen</label>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPurchasePrice" wire:model.live="showPurchasePrice">
                        <label class="form-check-label" for="showPurchasePrice">Inkoopprijs</label>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPriceCustomer" wire:model.live="showPriceCustomer">
                        <label class="form-check-label" for="showPriceCustomer">Verkoopprijs</label>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showProfit" wire:model.live="showProfit">
                        <label class="form-check-label" for="showProfit">Winst</label>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showMargin" wire:model.live="showMargin">
                        <label class="form-check-label" for="showMargin">Marge</label>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showTax" wire:model.live="showTax">
                        <label class="form-check-label" for="showTax">Belasting</label>
                    </div>
                </div>
                <div class="col mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showLink" wire:model.live="showLink">
                        <label class="form-check-label" for="showLink">Link</label>
                    </div>
                </div>
            </div>
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th wire:click="sortBy('name')">Naam @include('component.orderby.index' ,['field'=>'name'])</th>
                                        @if ($showProductImages == true)
                                            <th>Afbeelding</th>
                                        @endif
                                        @if ($showPurchasePrice == true)
                                            <th wire:click="sortBy('purchase_price_excluding_tax')">Inkoopprijs (excl. BTW) @include('component.orderby.index' ,['field'=>'purchase_price_excluding_tax'])</th>
                                        @endif
                                        @if ($showPriceCustomer == true)
                                            <th wire:click="sortBy('price_customer_excluding_tax')">Klantprijs (excl. BTW) @include('component.orderby.index' ,['field'=>'price_customer_excluding_tax'])</th>
                                            <th wire:click="sortBy('price_customer_including_tax')">Klantprijs (incl. BTW) @include('component.orderby.index' ,['field'=>'price_customer_including_tax'])</th>
                                        @endif
                                        @if ($showProfit == true)
                                            <th wire:click="sortBy('profit')">Winst @include('component.orderby.index' ,['field'=>'profit'])</th>
                                        @endif
                                        @if ($showMargin == true)
                                            <th>Marge</th>
                                        @endif
                                        @if ($showTax == true)
                                            <th wire:click="sortBy('tax_percentage')">Belasting @include('component.orderby.index' ,['field'=>'tax_percentage'])</th>
                                        @endif
                                        @if ($showLink == true)
                                            <th wire:click="sortBy('link')">Link @include('component.orderby.index' ,['field'=>'link'])</th>
                                        @endif
                                        <th>Actie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            @if ($showProductImages == true)
                                                <td>
                                                    @if ($product->hasMedia('product_images'))
                                                    <div class="w-px-200">
                                                        <img src="{{ $product->getFirstMediaUrl('product_images') }}" alt="Header afbeelding {{ $product->title }}" style="max-height: 150px; max-width: 100%;">
                                                    </div>
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($showPurchasePrice == true)
                                                <td>€{{ $product->purchase_price_excluding_tax }}</td>
                                            @endif
                                            @if ($showPriceCustomer == true)
                                                <td>@if ($product->use_discount_price == 1)
                                                        <s>€{{ $product->price_customer_excluding_tax }}</s><br>
                                                        <span class="text-danger">€{{ $product->discount_price_customer_excluding_tax }}</span>
                                                    @else
                                                        €{{ $product->price_customer_excluding_tax }}
                                                    @endif</td>
                                                    <td>@if ($product->use_discount_price == 1)
                                                        <s>€{{ $product->price_customer_including_tax }}</s><br>
                                                        <span class="text-danger">€{{ $product->discount_price_customer_including_tax }}</span>
                                                    @else
                                                        €{{ $product->price_customer_including_tax }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($showProfit == true)
                                                <td>€{{ $product->profit }}</td>
                                            @endif
                                            @if ($showMargin == true)
                                                @php
                                                    $margin = ($product->price_customer_excluding_tax != 0 ? (($product->price_customer_excluding_tax - $product->purchase_price_excluding_tax)/$product->price_customer_excluding_tax * 100) : 0)
                                                @endphp
                                                <td class="table-{{ $margin <= 20 ? 'danger' : ($margin <= 50 ? 'warning' : 'success') }}">{{ number_format($margin,1, ',', '.') }}%</td>
                                            @endif
                                            @if ($showTax == true)
                                                <td>{{ $product->tax_percentage }}%</td>
                                            @endif
                                            @if ($showLink == true)
                                                <td>
                                                    @if ($product->link != null)
                                                        <a href="{{ $product->link }}" target="_blank">{{ parse_url($product->link)['host'] }}</a>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-success" wire:click="clone('{{ $product->id }}')">
                                                        <i class="fas fa-copy"></i>
                                                    </a>
                                                    <a class="btn btn-primary" href="{{ route('product.show', $product->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-secondary" href="{{ route('product.edit', $product->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger" wire:click="deleteConfirm('{{ $product->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                <span class="font-weight-bold">Er zijn geen producten gevonden</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-auto mt-2">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


