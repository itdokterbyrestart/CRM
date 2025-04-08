<div class="card">
	<div class="card-body">
        <form wire:submit="store" class="my-1">
            <div class="row justify-content-between align-items-start mb-2">
                <div class="col-auto">
                    <a href="{{ route('product.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
                        <a class="btn btn-primary" href="{{ route('product.edit', $modelId) }}" tabindex="0">
                            Aanpassen
                        </a>
                    </div>
                @endif
            </div>

            <div class="row">
                @if ($edit === 0)
                    <div class="col-12 col-md-6 mb-1">
                        <label for="id" class="col-form-label col-form-label-md">ID</label>
                        <input wire:model.blur="modelId" type="number" class="form-control form-control-md" tabindex="0" disabled>
                    </div>
                @endif
            
            

                <div class="col-12 @if ($edit === 0) col-md-6 @endif mb-1">
                    <label for="name" class="col-form-label col-form-label-md">Naam</label>
                    <input wire:model.blur="name" type="text" class="form-control form-control-md @error('name') is-invalid @enderror" id="name" required autofocus tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label for="purchase_price_excluding_tax" class="col-form-label col-form-label-md">Inkoopprijs excl. BTW</label>
                    <input wire:model.blur="purchase_price_excluding_tax" type="number" class="form-control form-control-md @error('purchase_price_excluding_tax') is-invalid @enderror" id="purchase_price_excluding_tax" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                    @error('purchase_price_excluding_tax')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label for="purchase_price_including_tax" class="col-form-label col-form-label-md">Inkoopprijs incl. BTW</label>
                    <input wire:model.blur="purchase_price_including_tax" type="number" class="form-control form-control-md @error('purchase_price_including_tax') is-invalid @enderror" id="purchase_price_including_tax" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                    @error('purchase_price_including_tax')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label for="profit" class="col-form-label col-form-label-md">Winst</label>
                    <input wire:model.blur="profit" type="number" class="form-control form-control-md @error('profit') is-invalid @enderror" id="profit" tabindex="0" @if ($edit === 0) disabled @endif min="-99999999.99" max="99999999.99" step="0.01">
                    @error('profit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label for="tax_percentage" class="col-form-label col-form-label-md">Belasting</label>
                    <select wire:model.blur="tax_percentage" class="form-control form-control-md @error('tax_percentage') is-invalid @enderror" id="tax_percentage" @if ($edit === 0) disabled @endif>
                        <option value="" disabled hidden>Kies een belastingtype</option>
                        @foreach ($tax_types as $tax_type)
                            <option value="{{ $tax_type->percentage }}">{{ $tax_type->name }} ({{ $tax_type->percentage }}%)</option>
                        @endforeach
                    </select>
                    @error('tax_percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label for="price_customer_excluding_tax" class="col-form-label col-form-label-md">Klantprijs excl. BTW</label>
                    <input wire:model.blur="price_customer_excluding_tax" type="number" class="form-control form-control-md @error('price_customer_excluding_tax') is-invalid @enderror" id="price_customer" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                    @error('price_customer_excluding_tax')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 mb-1">
                    <label for="price_customer_including_tax" class="col-form-label col-form-label-md">Klantprijs incl. BTW</label>
                    <input wire:model.blur="price_customer_including_tax" type="number" class="form-control form-control-md @error('price_customer_including_tax') is-invalid @enderror" id="price_customer_including_tax" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                    @error('price_customer_including_tax')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="form-check @if ($use_discount_price == true) mb-1 @endif">
                        <input wire:model.blur="use_discount_price" type="checkbox" id="use_discount_price" class="form-check-input" @if ($edit === 0) disabled @endif>
                        <label for="use_discount_price" class="form-check-label">Gebruik kortingsprijs</label>
                        @error('use_discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($use_discount_price == true)
                    <div class="col-12 col-sm-6 mb-1">
                        <label for="discount_price_customer_excluding_tax" class="col-form-label col-form-label-md">Kortingsprijs excl. BTW</label>
                        <input wire:model.blur="discount_price_customer_excluding_tax" type="number" class="form-control form-control-md @error('discount_price_customer_excluding_tax') is-invalid @enderror" id="discount_price_customer_excluding_tax" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                        @error('discount_price_customer_excluding_tax')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-sm-6 mb-1">
                        <label for="discount_price_customer_including_tax" class="col-form-label col-form-label-md">Kortingsprijs incl. BTW</label>
                        <input wire:model.blur="discount_price_customer_including_tax" type="number" class="form-control form-control-md @error('discount_price_customer_including_tax') is-invalid @enderror" id="discount_price_customer_including_tax" tabindex="0" @if ($edit === 0) disabled @endif min="-99999.99" max="99999.99" step="0.01">
                        @error('discount_price_customer_including_tax')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="col-12 mb-1">
                    <label for="link" class="col-form-label col-form-label-md">Link</label>
                    <input wire:model.blur="link" type="text" class="form-control form-control-md @error('link') is-invalid @enderror" id="link" tabindex="0" @if ($edit === 0) disabled @endif>
                    @error('link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-1">
                    <label for="description" class="col-form-label col-form-label-md">Beschrijving</label>
                    <textarea wire:model.blur="description" type="text" class="form-control form-control-md @error('description') is-invalid @enderror" id="description" tabindex="0" rows="{{ $description_count }}" @if ($edit === 0) disabled @endif>
                    </textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <h3>Product groepen</h3>
            @error('selected_product_groups')
                <div class="alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-1">
                <div class="row">
                    @foreach ($product_groups as $product_group)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="form-check">
                                <input wire:model.blur="selected_product_groups.{{ $product_group->id }}" type="checkbox" id="product_group.{{ $product_group->id }}" class="form-check-input" @if ($edit === 0) disabled @endif>
                                <label for="product_group.{{ $product_group->id }}" class="form-check-label">{{ $product_group->name }}</label>
                                @error('selected_product_groups.' . $product_group->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- Project images --}}
            <div class="mt-2">
                <h3 @error('images') class="text-danger" @enderror>Afbeeldingen</h3>
            </div>

            {{-- Upload images --}}
            <div class="mb-3 custom-file">
                @if ($edit == 1)
                    <label for="new_images" class="form-label custom-file-label">Afbeeldingen toevoegen</label>
                    {{-- <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="De afbeelding is vierkant na het opslaan, alles wat geen afbeelding is wordt gevuld met een transparante achtergrond."><i class="fa fa-circle-info"></i></span> --}}
                    <input class="form-control custom-file-input @error('new_images.*') is-invalid @enderror @error('images') is-invalid @enderror" type="file" id="new_images" wire:model.live.debounce.500ms="new_images" tabindex="0" multiple> 
                    @error('new_images.*')
                        <div class="invalid-feedback" for="new_images">{{ $message }}</div>
                    @enderror
                    @error('images')
                        <div class="invalid-feedback" for="new_images">{{ $message }}</div>
                    @enderror
                    <div wire:target="new_images" class="d-none spinner-border text-primary" wire:loading.class.remove="d-none" role="status">
                        <span class="sr-only">Uploaden...</span>
                    </div>
                @endif
            </div>

            <div class="mb-1" >
                <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 g-4" @if ($edit == 1) wire:sortable="orderUpdated" wire:sortable.options="{ animation: 100 }" @endif  >
                    @foreach ($image_order as $index => $image)
                        <div class="col" @if ($edit == 1) wire:sortable.item="{{ $index }}" wire:key="index-{{ $index }}" wire:sortable.handle @endif>
                            <div class="card h-100">
                                <div class="ratio ratio-16x9">
                                    <img src="{{ asset($image_link[$index]) }}" class="card-img-top" alt="Product afbeelding {{ $loop->iteration }}" style="vertical-align: middle; object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Volgorde: {{ $image_order[$index] }}</p>
                                    @if ($edit == 1)
                                        <div wire:click="removeImage({{ $index }})" class="btn btn-danger">
                                            <span wire:target="removeImage.{{ $index }}" wire:loading.class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
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

            @if ($edit === 0)
                <h3>Services</h3>

                <div class="col-auto mb-1">
                    <div class="row">
                        @forelse ($services as $service)
                            <div class="col-12">
                                <p>- {{ $service->name }}</p>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>Er zijn geen services gekoppeld aan dit product</p>
                            </div>
                        @endforelse
                    </div>
                </div>   
            @endif



            @if ($edit === 0)
                <div class="col-auto mb-1">
                    <label for="updated_at" class="col-form-label col-form-label-md">Aangepast op</label>
                    <input wire:model.blur="updated_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
                </div>
            @endif

            @if ($edit === 0)
                <div class="col-auto mb-1">
                    <label for="created_at" class="col-form-label col-form-label-md">Gemaakt op</label>
                    <input wire:model.blur="created_at" type="text" class="form-control form-control-md" tabindex="0" disabled>
                </div>
            @endif

            @if ($edit === 1)
                <hr>

                <div class="row">
                    <div class="col-12 mt-1">
                        <a href="{{ route('product.index') }}" wire:loading.attr="disabled" type="button" class="btn btn-danger" tabindex="0">
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
